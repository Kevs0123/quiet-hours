@extends('layouts.app')
@section('title', 'Upload Confirmation')
@section('content')

<style>
    .booking-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        padding: 28px 30px;
        border-radius: 20px;
        background: linear-gradient(135deg, #1b2a4a 0%, #2b426f 100%);
        color: #fff;
        box-shadow: 0 16px 40px rgba(27,42,74,0.12);
    }
    .booking-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(201,161,90,0.24), transparent 35%);
        pointer-events: none;
    }
    .booking-hero-content {
        position: relative;
        z-index: 1;
        max-width: 760px;
    }
    .booking-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.16);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .booking-hero h1 {
        margin: 0 0 8px;
        font-size: 28px;
        color: #fff;
    }
    .booking-hero p {
        margin: 0;
        color: rgba(255,255,255,0.86);
        line-height: 1.6;
        font-size: 14px;
    }
    .booking-shell {
        max-width: 1000px;
        margin: 0 auto;
        padding: 24px;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(27,42,74,0.05);
    }
    .booking-intro {
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid #efe7da;
    }
    .booking-intro h2 {
        margin: 0 0 6px;
        color: #1b2a4a;
        font-size: 22px;
    }
    .booking-intro p {
        margin: 0;
        color: #6b6860;
        font-size: 14px;
    }
    .booking-recap {
        background: #fdf6ec;
        border: 1px solid #ead7ab;
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 20px;
        color: #5b4c25;
    }
    .booking-recap-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #a6833e;
        margin-bottom: 10px;
    }
    .booking-recap-grid {
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:8px 12px;
        font-size:13px;
    }
    @media (max-width: 768px) {
        .booking-hero { padding: 22px; }
        .booking-shell { padding: 18px; }
        .booking-recap-grid { grid-template-columns:1fr; }
    }
</style>

<div class="booking-hero">
    <div class="booking-hero-content">
        <div class="booking-pill">Step 2 of 4</div>
        <h1>Upload your confirmation</h1>
        <p>Share your booking confirmation in just a few clicks, then continue to the final review.</p>
    </div>
</div>

<div class="card booking-shell">
    <div class="wizard-steps">
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Welcome</span></div>
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Details</span></div>
        <div class="wizard-step active"><span class="step-num">3</span><span class="step-label">Confirmation</span></div>
        <div class="wizard-step"><span class="step-num">4</span><span class="step-label">Summary</span></div>
    </div>

    <div class="booking-intro">
        <h2>Confirmation upload</h2>
        <p>Accepted files are PDF, JPG, and PNG, up to 2MB in size.</p>
    </div>

    @if ($details)
    <div class="booking-recap">
        <div class="booking-recap-title">Booking recap</div>
        <div class="booking-recap-grid">
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
