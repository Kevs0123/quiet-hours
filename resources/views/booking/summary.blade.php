@extends('layouts.app')
@section('title', 'Booking Summary')
@section('content')

@php
    $nights = $booking->check_in_date && $booking->check_out_date
        ? $booking->check_in_date->diffInDays($booking->check_out_date) : 0;
    $total  = $booking->room ? $booking->room->price_per_night * max($nights, 1) : $booking->amount_paid;
@endphp

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
        background: radial-gradient(circle at top right, rgba(201,161,90,0.24), transparent 34%);
        pointer-events: none;
    }
    .booking-hero-content { position: relative; z-index: 1; max-width: 760px; }
    .booking-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.16); border: 1px solid rgba(255,255,255,0.25);
        border-radius: 999px; padding: 6px 12px; font-size: 11px; font-weight: 700;
        letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;
    }
    .booking-hero h1 { margin: 0 0 8px; font-size: 28px; color: #fff; }
    .booking-hero p { margin: 0; color: rgba(255,255,255,0.86); line-height: 1.6; font-size: 14px; }

    .wizard-steps{display:flex;border-radius:10px;overflow:hidden;margin-bottom:24px;box-shadow:0 2px 8px rgba(27,42,74,.08);}
    .wizard-step{flex:1;padding:13px 10px;text-align:center;font-size:13px;font-weight:600;background:var(--cream2);color:var(--muted);}
    .wizard-step.done{background:var(--gold);color:var(--navy);}
    .wizard-step.active{background:var(--navy);color:#fff;}
    .wizard-step .step-num{display:block;font-size:18px;font-family:'Playfair Display',serif;margin-bottom:2px;}
    .wizard-step .step-label{font-size:11px;text-transform:uppercase;letter-spacing:.4px;}

    .s-banner{
        display:flex;align-items:center;gap:14px;
        border-radius:12px;
        padding:14px 18px;margin-bottom:24px;font-size:14px;
        animation:fadeDown .4s ease;
    }
    .s-banner strong{font-size:14px;}
    .s-banner.pending{background:#fdf6ec;border:1px solid #ead7ab;color:#7a5c1e;}
    .s-banner.confirmed{background:#e8f5ee;border:1px solid #bddecb;color:#1a5c32;}
    .s-banner.rejected{background:#fde9e7;border:1px solid #f3c0bb;color:#8b241d;}

    .s-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:24px;
        align-items:start;
        max-width:1000px;
        margin:0 auto;
    }
    @media(max-width:880px){.s-grid{grid-template-columns:1fr;}}

    .s-panel{
        border:1px solid var(--cream2);
        border-radius:14px;
        overflow:hidden;
        background:#fff;
        box-shadow:0 10px 30px rgba(27,42,74,0.05);
    }
    .s-panel-head{
        display:flex;align-items:center;gap:8px;
        padding:13px 18px;
        background:var(--cream);
        border-bottom:1px solid var(--cream2);
        font-size:13px;font-weight:700;
        text-transform:uppercase;letter-spacing:.6px;color:var(--navy);
    }

    .d-row{
        display:flex;align-items:flex-start;gap:10px;
        padding:12px 18px;
        border-bottom:1px solid var(--cream2);
        font-size:14px;
    }
    .d-row:last-child{border-bottom:none;}
    .d-ico{font-size:15px;flex-shrink:0;width:20px;text-align:center;padding-top:1px;}
    .d-lbl{color:var(--muted);font-size:13px;min-width:130px;flex-shrink:0;padding-top:1px;}
    .d-val{color:var(--navy);font-weight:600;flex:1;}

    .bk-chip{
        display:inline-block;background:var(--navy);color:#fff;
        font-family:monospace;font-size:13px;font-weight:700;
        letter-spacing:2px;padding:3px 10px;border-radius:6px;
    }
    .nights-tag{
        display:inline-flex;align-items:center;gap:4px;
        background:#fdf6ec;color:var(--gold2);
        font-size:12px;font-weight:700;
        padding:2px 9px;border-radius:20px;
        border:1px solid #e8c97a;margin-left:6px;
    }

    .status-block{padding:24px 20px;text-align:center;}
    .status-icon{width:76px;height:76px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:32px;}
    .status-icon.pending{background:#fdf6ec;}
    .status-icon.confirmed{background:#e6f5ee;}
    .status-icon.rejected{background:#fde9e7;}
    .status-title{font-size:16px;font-weight:700;color:var(--navy);margin-bottom:6px;}
    .status-desc{font-size:13px;color:var(--muted);line-height:1.6;margin-bottom:16px;}

    .btn-dl-pdf{
        display:flex;align-items:center;justify-content:center;gap:8px;
        padding:12px 18px;width:100%;
        background:#c62828;color:#fff;
        font-size:14px;font-weight:700;border-radius:8px;
        text-decoration:none;
        transition:background .2s,transform .2s;
        font-family:'Inter',sans-serif;
    }
    .btn-dl-pdf:hover{background:#b71c1c;transform:translateY(-1px);}

    .reject-note{
        text-align:left;background:var(--cream);border-radius:8px;padding:12px 14px;
        font-size:13px;color:var(--ink);margin-top:6px;
    }

    .s-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px;}
</style>

<div style="max-width:900px;margin:0 auto;">

    <div class="booking-hero">
        <div class="booking-hero-content">
            <div class="booking-pill">Step 3 of 4</div>
            <h1>
                @if($booking->isConfirmed()) Your booking is confirmed
                @elseif($booking->isRejected()) There's an issue with your booking
                @else Payment received — awaiting confirmation
                @endif
            </h1>
            <p>
                @if($booking->isConfirmed())
                    Everything has been verified and confirmed by our team. Your stay is locked in.
                @elseif($booking->isRejected())
                    Please review the note below from our team and reach out if you have questions.
                @else
                    We've received your payment details. An admin will review and confirm your booking shortly.
                @endif
            </p>
        </div>
    </div>

    <div class="wizard-steps">
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Welcome</span></div>
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Details</span></div>
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Payment</span></div>
        <div class="wizard-step active"><span class="step-num">3</span><span class="step-label">Summary</span></div>
    </div>

    @if($booking->isConfirmed())
        <div class="s-banner confirmed">
            <span style="font-size:20px;">✅</span>
            <div><strong>Booking Confirmed!</strong> Your reservation is finalized. A confirmation email with a PDF copy has been sent to you.</div>
        </div>
    @elseif($booking->isRejected())
        <div class="s-banner rejected">
            <span style="font-size:20px;">⚠️</span>
            <div><strong>Booking Not Confirmed.</strong> See the details from our team below.</div>
        </div>
    @else
        <div class="s-banner pending">
            <span style="font-size:20px;">⏳</span>
            <div><strong>Pending Admin Confirmation.</strong> Your payment was received — we're just verifying it. Refresh this page later to check for updates.</div>
        </div>
    @endif

    <div class="s-grid">

        <div class="s-panel">
            <div class="s-panel-head">📋 &nbsp;Booking Details</div>

            <div class="d-row">
                <div class="d-ico">👤</div>
                <div class="d-lbl">Customer Name</div>
                <div class="d-val">{{ $booking->customer_name }}</div>
            </div>
            <div class="d-row">
                <div class="d-ico">#</div>
                <div class="d-lbl">Booking ID</div>
                <div class="d-val"><span class="bk-chip">{{ $booking->booking_id }}</span></div>
            </div>
            <div class="d-row">
                <div class="d-ico">📅</div>
                <div class="d-lbl">Check In Date</div>
                <div class="d-val">{{ $booking->check_in_date?->format('F j, Y') }}</div>
            </div>
            <div class="d-row">
                <div class="d-ico">📅</div>
                <div class="d-lbl">Check Out Date</div>
                <div class="d-val">{{ $booking->check_out_date?->format('F j, Y') }}</div>
            </div>
            <div class="d-row">
                <div class="d-ico">🌙</div>
                <div class="d-lbl">Nights</div>
                <div class="d-val">
                    {{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}
                    @if($total)
                        <span class="nights-tag">₱{{ number_format($total, 2) }} total</span>
                    @endif
                </div>
            </div>
            <div class="d-row">
                <div class="d-ico">🛏</div>
                <div class="d-lbl">Room Type</div>
                <div class="d-val">
                    {{ $booking->room->name ?? $booking->event_name }}
                    @if($booking->room?->category)
                        <div style="font-size:12px;color:var(--muted);font-weight:400;">{{ $booking->room->category->name }}</div>
                    @endif
                </div>
            </div>
            <div class="d-row">
                <div class="d-ico">👥</div>
                <div class="d-lbl">Number of Guests</div>
                <div class="d-val">{{ $booking->number_of_persons }} {{ $booking->number_of_persons == 1 ? 'guest' : 'guests' }}</div>
            </div>
        </div>

        {{-- RIGHT: Payment & Status --}}
        <div class="s-panel">
            <div class="s-panel-head">💳 &nbsp;Payment &amp; Status</div>

            <div class="d-row">
                <div class="d-ico">💳</div>
                <div class="d-lbl">Method</div>
                <div class="d-val">{{ $booking->paymentMethodLabel() }}</div>
            </div>
            <div class="d-row">
                <div class="d-ico">🔖</div>
                <div class="d-lbl">Reference No.</div>
                <div class="d-val" style="font-family:monospace;">{{ $booking->payment_reference ?? '—' }}</div>
            </div>
            @if($booking->amount_paid)
            <div class="d-row">
                <div class="d-ico">💰</div>
                <div class="d-lbl">Amount</div>
                <div class="d-val">₱{{ number_format($booking->amount_paid, 2) }}</div>
            </div>
            @endif
            <div class="d-row">
                <div class="d-ico">📌</div>
                <div class="d-lbl">Status</div>
                <div class="d-val">
                    <span class="badge {{ $booking->statusBadgeClass() }}">{{ $booking->statusLabel() }}</span>
                </div>
            </div>

            <div class="status-block">
                @if($booking->isConfirmed())
                    <div class="status-icon confirmed">✅</div>
                    <div class="status-title">All set!</div>
                    <div class="status-desc">Download a PDF copy of your confirmation for your records.</div>
                    <a href="{{ route('booking.history.pdf', $booking) }}" class="btn-dl-pdf">⬇ &nbsp;Download Confirmation PDF</a>
                @elseif($booking->isRejected())
                    <div class="status-icon rejected">⚠️</div>
                    <div class="status-title">Booking not confirmed</div>
                    <div class="status-desc">Our team left a note about this booking:</div>
                    <div class="reject-note">{{ $booking->admin_notes }}</div>
                @else
                    <div class="status-icon pending">⏳</div>
                    <div class="status-title">Awaiting confirmation</div>
                    <div class="status-desc">We typically review and confirm bookings within a short time. You'll receive an email as soon as it's confirmed.</div>
                @endif
            </div>
        </div>

    </div>

    {{-- Actions --}}
    <div class="s-actions">
        @if($booking->isRejected())
            <a href="{{ route('booking.reset') }}" class="btn btn-gold">✦ Start a New Booking</a>
        @else
            <a href="{{ route('booking.reset') }}" class="btn btn-outline">✦ Start Another Booking</a>
        @endif
        <a href="{{ route('booking.dashboard') }}" class="btn btn-outline">My Dashboard</a>
        <a href="{{ route('home') }}" class="btn btn-outline">Back to Home</a>
    </div>

</div>
@endsection
