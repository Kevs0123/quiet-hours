@component('mail::message')
# Booking Confirmation

Dear {{ $booking->customer_name }},

Your booking has been confirmed! We're delighted to have you with us.

@component('mail::panel')
**Booking Reference:** {{ $booking->booking_id }}

**Guest Name:** {{ $booking->customer_name }}

**Check-In:** {{ $booking->check_in_date->format('M d, Y') }}

**Check-Out:** {{ $booking->check_out_date->format('M d, Y') }}

**Room:** {{ $booking->room->name }} ({{ $booking->room->category->name }})

**Duration:** {{ $booking->nights }} {{ $booking->nights === 1 ? 'night' : 'nights' }}

**Number of Guests:** {{ $booking->number_of_persons }}

**Total Amount:** ₱{{ number_format($booking->room->price_per_night * $booking->nights, 2) }}
@endcomponent

A detailed PDF confirmation is attached to this email. Please keep it for your records.

If you have any questions or need to make changes to your booking, please don't hesitate to contact us.

@component('mail::button', ['url' => route('booking.history')])
View Your Bookings
@endcomponent

Best regards,

**Quiet Hours Team**

---

*This is an automated email. Please do not reply directly to this message.*
@endcomponent
