<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $allBookings = Booking::with('room')->get();

        // Calculate total booked dates (each booking represents check_in to check_out range)
        $totalBookedDates = 0;
        foreach ($allBookings as $booking) {
            if ($booking->check_in_date && $booking->check_out_date) {
                $days = $booking->check_in_date->diffInDays($booking->check_out_date);
                $totalBookedDates += max($days, 1);
            }
        }

        // Build FullCalendar-compatible event objects for every booking
        $calendarEvents = $allBookings
            ->filter(fn ($b) => $b->check_in_date && $b->check_out_date)
            ->map(function ($booking) {
                $categoryName = $booking->room && $booking->room->category 
                    ? $booking->room->category->name 
                    : 'Standard';
                $roomName = $booking->room->name ?? $booking->event_name;
                return [
                    'title' => $categoryName . ' • ' . $roomName . ' — ' . $booking->customer_name,
                    // FullCalendar's `end` is exclusive, so use check_out_date as-is
                    // (check_out_date is the last night's *following* morning already)
                    'start' => $booking->check_in_date->format('Y-m-d'),
                    'end'   => $booking->check_out_date->format('Y-m-d'),
                    'color' => $booking->calendarColor(),
                    'url'   => route('admin.bookings.show', $booking),
                ];
            })
            ->values();

        return view('admin.dashboard', array_merge($this->statCounts(), [
            'recentBookings'  => Booking::with('user')->latest()->take(8)->get(),
            'calendarEvents'  => $calendarEvents->toJson(),
        ]));
    }

    /**
     * JSON endpoint the dashboard polls periodically so the stat cards
     * reflect the live database state without a full page reload.
     */
    public function dashboardStats()
    {
        return response()->json($this->statCounts());
    }

    private function statCounts(): array
    {
        $allBookings = Booking::all(['check_in_date', 'check_out_date']);

        $totalBookedDates = 0;
        foreach ($allBookings as $booking) {
            if ($booking->check_in_date && $booking->check_out_date) {
                $days = $booking->check_in_date->diffInDays($booking->check_out_date);
                $totalBookedDates += max($days, 1);
            }
        }

        return [
            'totalBookings'    => Booking::count(),
            'totalRooms'       => Room::count(),
            'totalCategories'  => RoomCategory::count(),
            'totalClients'     => User::where('role', 'client')->count(),
            'totalUsers'       => User::count(),
            'totalBookedDates' => $totalBookedDates,
        ];
    }

    public function bookings(\Illuminate\Http\Request $request)
    {
        $status = $request->query('status');

        $query = Booking::with(['user', 'room.category'])->latest();

        if (in_array($status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_REJECTED], true)) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings', compact('bookings', 'status'));
    }

    public function bulkConfirmBookings(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'booking_ids'   => ['required', 'array', 'min:1'],
            'booking_ids.*' => ['integer', 'exists:bookings,id'],
        ]);

        $bookings = Booking::whereIn('id', $data['booking_ids'])
            ->where('status', Booking::STATUS_PENDING)
            ->get();

        foreach ($bookings as $booking) {
            $booking->update([
                'status'       => Booking::STATUS_CONFIRMED,
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
                'admin_notes'  => null,
            ]);

            $this->sendBookingConfirmationEmails($booking);
        }

        $count = $bookings->count();

        return back()->with('success', "Confirmed {$count} booking(s). Guests have been notified by email.");
    }

    public function showBooking(Booking $booking)
    {
        $booking->load(['user', 'room.category', 'confirmedBy']);

        return view('admin.booking-show', compact('booking'));
    }

    public function editBooking(Booking $booking)
    {
        $booking->load(['user', 'room.category']);

        return view('admin.booking-edit', compact('booking'));
    }

    public function updateBooking(\Illuminate\Http\Request $request, Booking $booking)
    {
        $data = $request->validate([
            'customer_name'     => ['required', 'string', 'min:2', 'max:100'],
            'event_name'        => ['required', 'string', 'min:3', 'max:150'],
            'check_in_date'     => ['required', 'date'],
            'check_out_date'    => ['required', 'date', 'after:check_in_date'],
            'number_of_persons' => ['required', 'integer', 'min:1'],
        ]);

        $booking->update($data);

        return redirect()->route('admin.bookings')
            ->with('success', "Booking {$booking->booking_id} updated successfully.");
    }

    public function destroyBooking(Booking $booking)
    {
        // Delete the stored file from disk, if this older booking has one
        if ($booking->confirmation_file_path
            && \Illuminate\Support\Facades\Storage::disk('public')->exists($booking->confirmation_file_path)
        ) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($booking->confirmation_file_path);
        }

        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }

    /**
     * Admin sign-off step: a booking only becomes "confirmed" — and only
     * then does the client's summary page unlock the full confirmation +
     * PDF — once an admin reviews the payment details and approves it here.
     */
    public function confirmBooking(Booking $booking)
    {
        $booking->update([
            'status'       => Booking::STATUS_CONFIRMED,
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
            'admin_notes'  => null,
        ]);

        $this->sendBookingConfirmationEmails($booking);

        return back()->with('success', "Booking {$booking->booking_id} confirmed. The guest has been notified by email.");
    }

    private function sendBookingConfirmationEmails(Booking $booking): void
    {
        $booking->load('room.category');

        $recipientEmails = [];

        if ($booking->user?->email) {
            $recipientEmails[] = $booking->user->email;
        }

        if (auth()->user()?->isAdmin() && auth()->user()->email) {
            $recipientEmails[] = auth()->user()->email;
        }

        foreach (array_unique($recipientEmails) as $email) {
            try {
                \Illuminate\Support\Facades\Mail::to($email)
                    ->send(new \App\Mail\BookingConfirmationMail($booking));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send booking confirmation email', [
                    'booking_id' => $booking->booking_id,
                    'email'      => $email,
                    'error'      => $e->getMessage(),
                ]);
            }
        }
    }

    public function rejectBooking(\Illuminate\Http\Request $request, Booking $booking)
    {
        $data = $request->validate([
            'admin_notes' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'admin_notes.required' => 'Please explain why this booking is being rejected.',
        ]);

        $booking->update([
            'status'       => Booking::STATUS_REJECTED,
            'admin_notes'  => $data['admin_notes'],
            'confirmed_at' => null,
            'confirmed_by' => auth()->id(),
        ]);

        if ($booking->user?->email) {
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Hi {$booking->customer_name},\n\n".
                    "Unfortunately your booking {$booking->booking_id} could not be confirmed.\n\n".
                    "Reason: {$data['admin_notes']}\n\n".
                    "Please contact us or start a new booking if you'd like to try again.\n\n".
                    "— Quiet Hours Hotel",
                    function ($message) use ($booking) {
                        $message->to($booking->user->email)
                            ->subject("Booking {$booking->booking_id} could not be confirmed");
                    }
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send booking rejection email', [
                    'booking_id' => $booking->booking_id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        return back()->with('success', "Booking {$booking->booking_id} was rejected and the guest has been notified.");
    }
}
