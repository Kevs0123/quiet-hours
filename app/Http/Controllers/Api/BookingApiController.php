<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class BookingApiController extends Controller
{
    public function index(Request $request)
    {
        return Auth::user()->bookings()->with('room.category')->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'date_format:Y-m-d', 'after:check_in_date'],
            'number_of_persons' => ['required', 'integer', 'min:1'],
        ]);

        $room = Room::findOrFail($request->room_id);
        $request->validate([
            'number_of_persons' => ['max:'.$room->capacity],
        ]);

        $bookingId = strtoupper(bin2hex(random_bytes(4)));
        while (Booking::where('booking_id', $bookingId)->exists()) {
            $bookingId = strtoupper(bin2hex(random_bytes(4)));
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'customer_name' => Auth::user()->name,
            'booking_id' => $bookingId,
            'event_name' => $request->input('event_name', $room->name),
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'number_of_persons' => $request->number_of_persons,
            'confirmation_file_path' => $request->input('confirmation_file_path', ''),
            'confirmation_file_type' => $request->input('confirmation_file_type', ''),
        ]);

        return response()->json($booking, 201);
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        return $booking->load('room.category');
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $request->validate([
            'check_in_date' => ['sometimes', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'check_out_date' => ['sometimes', 'date', 'date_format:Y-m-d', 'after:check_in_date'],
            'number_of_persons' => ['sometimes', 'integer', 'min:1'],
            'room_id' => ['sometimes', 'exists:rooms,id'],
        ]);

        if ($request->has('room_id')) {
            $room = Room::findOrFail($request->room_id);
            $request->validate(['number_of_persons' => ['max:'.$room->capacity]]);
            $booking->room_id = $room->id;
        }

        if ($request->has('number_of_persons')) {
            $booking->number_of_persons = $request->number_of_persons;
        }
        if ($request->has('check_in_date')) {
            $booking->check_in_date = $request->check_in_date;
        }
        if ($request->has('check_out_date')) {
            $booking->check_out_date = $request->check_out_date;
        }

        $booking->save();

        return $booking;
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return response()->json(['message' => 'Booking deleted.']);
    }

    /**
     * POST /api/bookings/{booking}/send-summary
     * Generates the booking confirmation PDF and emails it to the
     * booking owner's registered email address.
     */
    public function sendSummary(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load('room.category', 'user');

        Mail::to($booking->user->email)->send(new BookingConfirmationMail($booking));

        return response()->json([
            'message' => 'Booking summary has been emailed to ' . $booking->user->email,
        ]);
    }
}
