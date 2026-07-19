@extends('layouts.app')
@section('title', 'Edit Booking — ' . $booking->booking_id)
@section('content')

<div class="card" style="max-width:580px;">
    <div class="page-header" style="margin-bottom:24px;">
        <div>
            <h1>Edit Booking</h1>
            <code style="background:var(--navy);color:#fff;padding:2px 10px;border-radius:5px;
                         font-size:13px;letter-spacing:1px;">{{ $booking->booking_id }}</code>
        </div>
        <a href="{{ route('admin.bookings') }}" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>

    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" novalidate>
        @csrf @method('PUT')

        <div class="field">
            <label>Customer Name <span style="color:var(--danger);">*</span></label>
            <input type="text" name="customer_name"
                   value="{{ old('customer_name', $booking->customer_name) }}"
                   @class(['is-invalid' => $errors->has('customer_name')])>
            @error('customer_name')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Event / Room Name <span style="color:var(--danger);">*</span></label>
            <input type="text" name="event_name"
                   value="{{ old('event_name', $booking->event_name) }}"
                   @class(['is-invalid' => $errors->has('event_name')])>
            @error('event_name')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="field">
                <label>Check-in Date <span style="color:var(--danger);">*</span></label>
                <input type="date" name="check_in_date"
                       id="edit_checkin"
                       value="{{ old('check_in_date', $booking->check_in_date?->format('Y-m-d')) }}"
                       @class(['is-invalid' => $errors->has('check_in_date')])>
                @error('check_in_date')<span class="error-text">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label>Check-out Date <span style="color:var(--danger);">*</span></label>
                <input type="date" name="check_out_date"
                       id="edit_checkout"
                       value="{{ old('check_out_date', $booking->check_out_date?->format('Y-m-d')) }}"
                       @class(['is-invalid' => $errors->has('check_out_date')])>
                @error('check_out_date')<span class="error-text">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field">
            <label>Number of Persons <span style="color:var(--danger);">*</span></label>
            <input type="number" name="number_of_persons" min="1"
                   value="{{ old('number_of_persons', $booking->number_of_persons) }}"
                   @class(['is-invalid' => $errors->has('number_of_persons')])>
            @error('number_of_persons')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div style="background:var(--cream);border-radius:8px;padding:12px 14px;
                    font-size:12px;color:var(--muted);margin-bottom:20px;">
            ℹ️ Payment details and confirmation status are managed on the booking detail page.
            <a href="{{ route('admin.bookings.show', $booking) }}" style="color:var(--navy);font-weight:600;">
                View payment &amp; confirm/reject →
            </a>
        </div>

        <div style="display:flex;gap:12px;">
            <a href="{{ route('admin.bookings') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Update Booking
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ci = document.getElementById('edit_checkin');
    const co = document.getElementById('edit_checkout');
    if (ci && co) {
        ci.addEventListener('change', function () {
            const next = new Date(this.value);
            next.setDate(next.getDate() + 1);
            co.min = next.toISOString().split('T')[0];
            if (co.value && co.value <= this.value) co.value = co.min;
        });
    }
});
</script>

@endsection
