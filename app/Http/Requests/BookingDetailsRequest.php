<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Validation\Validator;

class BookingDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Default max persons in case room is not found yet (will fail 'exists' rule first)
        $maxPersons = 999;

        $roomId = $this->input('room_id');
        if ($roomId) {
            $room = Room::find($roomId);
            if ($room && is_numeric($room->capacity)) {
                $maxPersons = (int) $room->capacity;
            }
        }

        return [
            'room_id'           => ['required', 'exists:rooms,id'],
            'check_in_date'     => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'check_out_date'    => ['required', 'date', 'date_format:Y-m-d', 'after:check_in_date'],
            'number_of_persons' => ['required', 'integer', 'min:1', 'max:'.$maxPersons],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required'            => 'Please select a room.',
            'room_id.exists'              => 'The selected room is no longer available.',
            'check_in_date.required'      => 'Please select a check-in date.',
            'check_in_date.date'          => 'The check-in date is not valid.',
            'check_in_date.after_or_equal'=> 'Check-in date must be today or a future date.',
            'check_out_date.required'     => 'Please select a check-out date.',
            'check_out_date.date'         => 'The check-out date is not valid.',
            'check_out_date.after'        => 'Check-out date must be after the check-in date.',
            'number_of_persons.required'  => 'Please enter the number of persons.',
            'number_of_persons.integer'   => 'The number of persons must be a whole number.',
            'number_of_persons.min'       => 'There must be at least 1 person.',
            'number_of_persons.max'       => 'The number of persons must not exceed the selected room capacity.',
        ];
    }

    /**
     * Defense-in-depth: even though the calendar UI blocks already-booked
     * dates from being picked, always re-check on the server too — the
     * client-side JS can be bypassed (devtools, disabled JS, direct POST).
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $roomId   = $this->input('room_id');
            $checkIn  = $this->input('check_in_date');
            $checkOut = $this->input('check_out_date');

            if (! $roomId || ! $checkIn || ! $checkOut) {
                return; // other rules already flagged the missing fields
            }

            $overlaps = Booking::where('room_id', $roomId)
                ->where('check_in_date', '<', $checkOut)
                ->where('check_out_date', '>', $checkIn)
                ->exists();

            if ($overlaps) {
                $validator->errors()->add(
                    'check_in_date',
                    'That room is already booked for one or more of the selected dates. Please choose different dates.'
                );
            }
        });
    }
}
