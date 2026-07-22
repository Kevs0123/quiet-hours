<?php
 
namespace App\Http\Controllers;
 
use App\Http\Requests\BookingDetailsRequest;
use App\Http\Requests\BookingPaymentRequest;
use App\Mail\BookingSummaryMail;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
 
class BookingController extends Controller
{
    public function home()
    {
        if (Auth::check() && Auth::user()->isClient()) {
            return redirect()->route('booking.start', [
                'customerName' => Auth::user()->name,
            ]);
        }
 
        return view('booking.home');
    }
 
    public function start(\Illuminate\Http\Request $request, string $customerName)
    {
        do {
            $bookingId = strtoupper(Str::random(8));
        } while (Booking::where('booking_id', $bookingId)->exists());
 
        session([
            'booking.customer_name' => $customerName,
            'booking.booking_id'    => $bookingId,
            'booking.step'          => 1,
        ]);
        session()->forget(['booking.details', 'booking.record_id']);

        // If a room id was provided from the home page, prefill the booking
        // details in session so the Details step shows the selected room.
        $roomId = $request->query('room_id');
        if ($roomId) {
            $room = Room::with('category')->find($roomId);
            if ($room) {
                session(["booking.details" => [
                    'room_id' => $room->id,
                    'event_name' => $room->name,
                    'room_name' => $room->name,
                    'category_name' => $room->category?->name,
                    'price_per_night' => $room->price_per_night,
                    // keep dates empty so user still picks them
                    'check_in_date' => null,
                    'check_out_date' => null,
                    'number_of_persons' => 1,
                ]]);
            }
        }
 
        return view('booking.start', [
            'customerName' => $customerName,
            'bookingId'    => $bookingId,
        ]);
    }
 
    public function showDetails()
    {
        // Load all available rooms grouped by category for the dropdown
        $categories = RoomCategory::with(['rooms' => function ($q) {
            $q->where('is_available', true)->orderBy('name');
        }])->orderBy('name')->get()->filter(fn ($c) => $c->rooms->isNotEmpty());
 
        return view('booking.details', [
            'customerName' => session('booking.customer_name'),
            'bookingId'    => session('booking.booking_id'),
            'savedDetails' => session('booking.details', []),
            'categories'   => $categories,
        ]);
    }
 
    public function storeDetails(BookingDetailsRequest $request)
    {
        $room = Room::with('category')->findOrFail($request->room_id);
 
        // Store room info into session details so downstream steps have full context
        session([
            'booking.details' => array_merge($request->validated(), [
                'event_name'    => $room->name,
                'room_name'     => $room->name,
                'category_name' => $room->category->name,
                'price_per_night' => $room->price_per_night,
            ]),
            'booking.step' => 2,
        ]);
 
        return redirect()
            ->route('booking.payment')
            ->with('success', 'Booking details saved. Please complete payment to reserve your room.');
    }
 
    /**
     * JSON endpoint: returns every booked date range for the given room so
     * the booking-form calendar can visually mark/block unavailable dates.
     * Each booking's check-out day itself is free for a new check-in, so we
     * report ranges as [check_in, check_out - 1 day] inclusive.
     */
    public function roomAvailability(Room $room)
    {
        $ranges = Booking::where('room_id', $room->id)
            ->whereNotNull('check_in_date')
            ->whereNotNull('check_out_date')
            ->get(['check_in_date', 'check_out_date'])
            ->map(function ($booking) {
                return [
                    'from' => $booking->check_in_date->format('Y-m-d'),
                    // last occupied night is the day before checkout
                    'to'   => $booking->check_out_date->copy()->subDay()->format('Y-m-d'),
                ];
            })
            ->values();
 
        return response()->json(['booked' => $ranges]);
    }
 
    /**
     * Step 2: payment. Replaces the old "upload your confirmation file" step
     * with a lightweight (mock) online-payment form -- the guest chooses a
     * payment method and supplies the reference/transaction number, and the
     * total due is computed live from the room rate and stay length.
     */
    public function showPayment()
    {
        $details = session('booking.details');
        $nights  = 1;
        $total   = null;

        if ($details) {
            $nights = max(
                \Carbon\Carbon::parse($details['check_in_date'])->diffInDays(\Carbon\Carbon::parse($details['check_out_date'])),
                1
            );
            if (isset($details['price_per_night'])) {
                $total = $details['price_per_night'] * $nights;
            }
        }

        return view('booking.payment', [
            'customerName' => session('booking.customer_name'),
            'bookingId'    => session('booking.booking_id'),
            'details'      => $details,
            'nights'       => $nights,
            'total'        => $total,
        ]);
    }

    public function storePayment(BookingPaymentRequest $request)
    {
        $details   = session('booking.details');
        $bookingId = session('booking.booking_id');

        if (! $details || ! $bookingId) {
            return redirect()->route('booking.home')->with('error', 'Session expired. Please start over.');
        }

        $nights = max(
            \Carbon\Carbon::parse($details['check_in_date'])->diffInDays(\Carbon\Carbon::parse($details['check_out_date'])),
            1
        );
        $amount = isset($details['price_per_night']) ? $details['price_per_night'] * $nights : null;

        $booking = Booking::create([
            'user_id'                => Auth::id(),
            'room_id'                => $details['room_id'],
            'customer_name'          => session('booking.customer_name'),
            'booking_id'             => $bookingId,
            'event_name'             => $details['event_name'],
            'check_in_date'          => $details['check_in_date'],
            'check_out_date'         => $details['check_out_date'],
            'number_of_persons'      => $details['number_of_persons'],
            'confirmation_file_path' => '',
            'confirmation_file_type' => '',
            'payment_method'         => $request->payment_method,
            'payment_reference'      => $request->payment_reference,
            'amount_paid'            => $amount,
            'status'                 => Booking::STATUS_PENDING,
        ]);

        session([
            'booking.record_id' => $booking->id,
            'booking.step'      => 3,
        ]);

        $this->sendBookingSummaryEmail($booking);

        return redirect()
            ->route('booking.summary')
            ->with('success', 'Payment received! Your booking is now awaiting admin confirmation.');
    }

    /**
     * Emails the booking summary (payment received, pending admin review)
     * to the account's registered email address. Failures are logged but
     * never block the booking flow -- the reservation is already saved.
     */
    protected function sendBookingSummaryEmail(Booking $booking): void
    {
        $recipient = Auth::user()?->email;

        if (! $recipient) {
            return;
        }

        try {
            $booking->load('room.category');
            Mail::to($recipient)->send(new BookingSummaryMail($booking));
        } catch (\Throwable $e) {
            Log::error('Failed to send booking summary email', [
                'booking_id' => $booking->booking_id,
                'recipient'  => $recipient,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * Step 3 (final): summary. Its content adapts to the booking's live
     * status -- pending, admin-confirmed, or admin-rejected -- since
     * confirmation now happens on the admin side, not automatically.
     */
    public function summary()
    {
        $booking = Booking::with('room.category')->find(session('booking.record_id'));

        if (! $booking) {
            return redirect()->route('booking.home')->with('error', 'We could not find that booking. Please start over.');
        }

        return view('booking.summary', [
            'booking' => $booking,
        ]);
    }
 
    public function history()
    {
        $user = auth()->user();
 
        return view('booking.history', [
            'bookings' => $user->bookings()->latest()->get(),
        ]);
    }
 
    /**
     * Client-facing dashboard: personal booking stats plus the system-wide
     * stats the rubric requires (total booked dates, total users), and a
     * read-only calendar of all booked dates so clients can see availability
     * before starting a new booking. Other clients' names are never shown —
     * only room + category — to avoid leaking who booked what.
     */
    public function dashboard()
    {
        $user = Auth::user();
 
        $myBookings = $user->bookings()->with('room.category')->latest()->get();
 
        $myTotalNights = 0;
        foreach ($myBookings as $booking) {
            if ($booking->check_in_date && $booking->check_out_date) {
                $myTotalNights += max($booking->check_in_date->diffInDays($booking->check_out_date), 1);
            }
        }
 
        $upcomingBooking = $myBookings
            ->filter(fn ($b) => $b->check_in_date && $b->check_in_date->isFuture())
            ->sortBy('check_in_date')
            ->first();
 
        $allBookings = Booking::with('room.category')
            ->whereNotNull('check_in_date')
            ->whereNotNull('check_out_date')
            ->get();
 
        $totalBookedDatesSystemWide = 0;
        foreach ($allBookings as $booking) {
            $totalBookedDatesSystemWide += max($booking->check_in_date->diffInDays($booking->check_out_date), 1);
        }
 
        // Privacy-safe calendar events — no other client's name is exposed,
        // only which room/category is taken on which dates.
        $calendarEvents = $allBookings->map(function ($booking) use ($user) {
            $isMine    = $booking->user_id === $user->id;
            $roomLabel = $booking->room->name ?? $booking->event_name;
 
            return [
                'title' => $isMine
                    ? '📍 Your stay — ' . $roomLabel
                    : $roomLabel . ' — Booked',
                'start' => $booking->check_in_date->format('Y-m-d'),
                'end'   => $booking->check_out_date->format('Y-m-d'),
                'color' => $isMine ? '#1a6b3a' : '#c9a86a',
            ];
        })->values();
 
        return view('booking.dashboard', [
            'myTotalBookings'            => $myBookings->count(),
            'myTotalNights'              => $myTotalNights,
            'upcomingBooking'            => $upcomingBooking,
            'totalBookedDatesSystemWide' => $totalBookedDatesSystemWide,
            'totalUsers'                 => User::count(),
            'calendarEvents'             => $calendarEvents->toJson(),
        ]);
    }
 
    /**
     * Session-independent version of the summary page, reachable from
     * Booking History at any time (not just right after the wizard) -- the
     * booking-step session flow above can expire, but a guest should always
     * be able to check whether their booking has been confirmed yet.
     */
    public function showBookingDetail(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load('room.category');

        return view('booking.summary', compact('booking'));
    }

    public function downloadBookingPdf(Booking $booking)
    {
        $this->authorize('view', $booking);

        if (! $booking->isConfirmed()) {
            abort(404, 'A confirmed booking PDF is not available yet.');
        }

        $booking->load('room.category');

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.booking-confirmation', [
            'booking' => $booking,
        ])->download("Booking-{$booking->booking_id}.pdf");
    }

    public function reset()
    {
        session()->forget([
            'booking.customer_name',
            'booking.booking_id',
            'booking.step',
            'booking.details',
            'booking.record_id',
        ]);
 
        return redirect()
            ->route('booking.home')
            ->with('success', 'You can start a new booking now.');
    }
}