@extends('layouts.app')
@section('title', 'Upload Confirmation')
@section('content')

<style>
    .booking-hero {
        position: relative;
        height: 340px;
        overflow: hidden;
        margin-bottom: 32px;
        background: linear-gradient(
            to bottom,
            rgba(10,18,40,0.05) 0%,
            rgba(10,18,40,0.08) 50%,
            rgba(10,18,40,0.15) 100%
        ), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1400&q=85&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    @media (max-width: 768px) {
        .booking-hero { height: 260px; background-attachment: scroll; }
    }
    @media (max-width: 480px) {
        .booking-hero { height: 200px; background-attachment: scroll; }
    }
</style>

<div class="booking-hero"></div>

<div class="card" style="max-width:1000px;margin:0 auto;">
    <div class="wizard-steps">
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Welcome</span></div>
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Details</span></div>
        <div class="wizard-step active"><span class="step-num">3</span><span class="step-label">Confirmation</span></div>
        <div class="wizard-step"><span class="step-num">4</span><span class="step-label">Summary</span></div>
    </div>

    <h1 style="margin-bottom:6px;">Step 2: Upload Confirmation</h1>
    <p style="margin-bottom:24px;">
        Upload your booking confirmation document.
        Accepted: <strong>PDF, JPG, PNG</strong> — max <strong>2MB</strong>.
    </p>

    @if ($details)
    <div style="background:var(--cream);border-radius:10px;padding:16px 20px;margin-bottom:24px;border-left:3px solid var(--gold);">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:12px;">Booking Recap</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px;">
            <div><span style="color:var(--muted);">Customer:</span> <strong>{{ session('booking.customer_name') }}</strong></div>
            <div><span style="color:var(--muted);">Booking ID:</span>
                <code style="background:#fff;padding:1px 7px;border-radius:4px;font-weight:700;letter-spacing:1px;">{{ $bookingId }}</code>
            </div>
            <div><span style="color:var(--muted);">Room:</span> <strong>{{ $details['room_name'] ?? $details['event_name'] }}</strong></div>
            <div><span style="color:var(--muted);">Category:</span> <strong>{{ $details['category_name'] ?? '—' }}</strong></div>
            <div><span style="color:var(--muted);">Check-in:</span> <strong>{{ \Carbon\Carbon::parse($details['check_in_date'])->format('M j, Y') }}</strong></div>
            <div><span style="color:var(--muted);">Check-out:</span> <strong>{{ \Carbon\Carbon::parse($details['check_out_date'])->format('M j, Y') }}</strong></div>
            <div><span style="color:var(--muted);">Persons:</span> <strong>{{ $details['number_of_persons'] }}</strong></div>
            @if(isset($details['price_per_night']))
            <div><span style="color:var(--muted);">Rate:</span> <strong>₱{{ number_format($details['price_per_night'], 2) }}/night</strong></div>
            @endif
        </div>
    </div>
    @endif

    <form action="{{ route('booking.confirmation.store') }}" method="POST"
          enctype="multipart/form-data" novalidate>
        @csrf
        <div class="field">
            <label>Confirmation File <span style="color:var(--danger);">*</span></label>
            <div class="file-drop-zone">
                <input type="file" name="confirmation_file" accept=".pdf,.jpg,.jpeg,.png">
                <div class="file-drop-icon">📎</div>
                <div class="file-drop-text">
                    <strong>Click to choose a file</strong> or drag and drop here
                </div>
                <div class="file-drop-text" style="margin-top:6px;font-size:12px;">PDF, JPG, PNG — max 2MB</div>
                <div id="file-name-display"></div>
            </div>
            @error('confirmation_file')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('booking.details') }}" class="btn btn-outline">&larr; Back</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Upload &amp; Continue &rarr;
            </button>
        </div>
    </form>
</div>

@endsection
