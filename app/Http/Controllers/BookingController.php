<?php
 
namespace App\Http\Controllers;
 
use App\Http\Requests\BookingConfirmationRequest;
use App\Http\Requests\BookingDetailsRequest;
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
 
    public function start(string $customerName)
    {
        do {
            $bookingId = strtoupper(Str::random(8));
        } while (Booking::where('booking_id', $bookingId)->exists());
 
        session([
            'booking.customer_name' => $customerName,
            'booking.booking_id'    => $bookingId,
            'booking.step'          => 1,
        ]);
        session()->forget(['booking.details', 'booking.confirmation', 'booking.record_id']);
 
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
            ->route('booking.confirmation')
            ->with('success', 'Booking details saved. Please upload your confirmation file.');
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
 
    public function showConfirmation()
    {
        return view('booking.confirmation', [
            'customerName' => session('booking.customer_name'),
            'bookingId'    => session('booking.booking_id'),
            'details'      => session('booking.details'),
        ]);
    }
 
    public function storeConfirmation(BookingConfirmationRequest $request)
    {
        $phpFileError = $_FILES['confirmation_file']['error'] ?? UPLOAD_ERR_NO_FILE;
 
        switch ($phpFileError) {
            case UPLOAD_ERR_OK:
                break; // good, continue
            case UPLOAD_ERR_NO_FILE:
                return back()->withErrors(['confirmation_file' => 'Please select a file to upload.']);
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return back()->withErrors(['confirmation_file' => 'The file is too large. Maximum size is 2 MB.']);
            case UPLOAD_ERR_NO_TMP_DIR:
                // Fallback: move manually to our own tmp dir
                $tmpDir = storage_path('tmp');
                if (! is_dir($tmpDir)) mkdir($tmpDir, 0755, true);
                ini_set('upload_tmp_dir', $tmpDir);
                // If still no file, show error
                if (! $request->hasFile('confirmation_file')) {
                    return back()->withErrors(['confirmation_file' => 'Server temp folder missing. Please contact support.']);
                }
                break;
            default:
                return back()->withErrors(['confirmation_file' => 'Upload failed. Please try again.']);
        }
 
        $file = $request->file('confirmation_file');
 
        if (! $file || ! $file->isValid()) {
            return back()->withErrors(['confirmation_file' => 'The file could not be read. Please try again.']);
        }
 
        $maxBytes = 2 * 1024 * 1024;
        $allowed  = ['pdf', 'jpg', 'jpeg', 'png'];
        $ext      = strtolower($file->getClientOriginalExtension());
 
        if (! in_array($ext, $allowed)) {
            return back()->withErrors(['confirmation_file' => 'Only PDF, JPG, and PNG files are allowed.']);
        }
 
        if ($file->getSize() > $maxBytes) {
            return back()->withErrors(['confirmation_file' => 'The file must not be larger than 2 MB.']);
        }
 
        // Ensure destination folder exists
        $destDir = storage_path('app/public/booking_confirmations');
        if (! is_dir($destDir)) mkdir($destDir, 0755, true);
 
        // Generate explicit filename
        $ext = strtolower($file->getClientOriginalExtension());
        if (!$ext) {
            return back()->withErrors(['confirmation_file' => 'Could not determine file type. Please try again.'])->withInput();
        }
        
        $bookingId = session('booking.booking_id');
        if (!$bookingId) {
            return back()->withErrors(['confirmation_file' => 'Session expired. Please start over.'])->withInput();
        }
        
        $filename = $bookingId . '_' . time() . '.' . $ext;
        $fullPath = $destDir . '/' . $filename;
        
        try {
            // Move file using the UploadedFile move method
            $file->move($destDir, $filename);
            
            // Store relative path for database
            $path = 'booking_confirmations/' . $filename;
        } catch (\Exception $e) {
            return back()
                ->withErrors(['confirmation_file' => 'The file could not be saved. Please try again.'])
                ->withInput();
        }
 
        $type = $ext;
        $details = session('booking.details');
 
        $booking = Booking::create([
            'user_id'                => Auth::id(),
            'room_id'                => $details['room_id'],
            'customer_name'          => session('booking.customer_name'),
            'booking_id'             => session('booking.booking_id'),
            'event_name'             => $details['event_name'],
            'check_in_date'          => $details['check_in_date'],
            'check_out_date'         => $details['check_out_date'],
            'number_of_persons'      => $details['number_of_persons'],
            'confirmation_file_path' => $path,
            'confirmation_file_type' => $type,
        ]);
 
        session([
            'booking.confirmation' => [
                'path'          => $path,
                'type'          => $type,
                'original_name' => $file->getClientOriginalName(),
            ],
            'booking.record_id' => $booking->id,
            'booking.step'      => 3,
        ]);
 
        $this->sendBookingSummaryEmail($booking);
 
        return redirect()
            ->route('booking.summary')
            ->with('success', 'Booking confirmed! Here is your summary.');
    }
 
    /**
     * Emails the full booking summary (with confirmation file attached, if any)
     * to the account's Gmail address via SMTP. Failures are logged but never
     * block the booking flow — the reservation is already saved at this point.
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
 
    public function summary()
    {
        return view('booking.summary', [
            'customerName' => session('booking.customer_name'),
            'bookingId'    => session('booking.booking_id'),
            'details'      => session('booking.details'),
            'confirmation' => session('booking.confirmation'),
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
 
    public function viewConfirmationFile()
    {
        $confirmation = session('booking.confirmation');
        if (! $confirmation || empty($confirmation['path'])) {
            abort(404, 'Confirmation file not found.');
        }
 
        $path = $confirmation['path'];
        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Confirmation file not found.');
        }
 
        return Storage::disk('public')->response($path);
    }
 
    public function downloadConfirmationFile()
    {
        $confirmation = session('booking.confirmation');
        if (! $confirmation || empty($confirmation['path']) || empty($confirmation['original_name'])) {
            abort(404, 'Confirmation file not found.');
        }
 
        $path = $confirmation['path'];
        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Confirmation file not found.');
        }
 
        return Storage::disk('public')->download($path, $confirmation['original_name']);
    }
 
    public function reset()
    {
        session()->forget([
            'booking.customer_name',
            'booking.booking_id',
            'booking.step',
            'booking.details',
            'booking.confirmation',
            'booking.record_id',
        ]);
 
        return redirect()
            ->route('booking.home')
            ->with('success', 'You can start a new booking now.');
    }
}