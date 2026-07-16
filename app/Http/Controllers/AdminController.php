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
                    'color' => '#c9a86a',
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

    public function bookings()
    {
        $bookings = Booking::with(['user', 'room.category'])->latest()->paginate(15);

        return view('admin.bookings', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        $booking->load(['user', 'room.category']);

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
        // Delete the stored file from disk
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($booking->confirmation_file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($booking->confirmation_file_path);
        }

        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }
}
