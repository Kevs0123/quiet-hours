@extends('layouts.app')
@section('title', 'Booking Summary')
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

@php
    $nights  = \Carbon\Carbon::parse($details['check_in_date'])->diffInDays($details['check_out_date']);
    $total   = isset($details['price_per_night']) ? (float)$details['price_per_night'] * $nights : null;
    $viewUrl = route('booking.summary.file.view');
    $downloadUrl = route('booking.summary.file.download');
    $isPdf   = strtolower($confirmation['type']) === 'pdf';
    $isImage = in_array(strtolower($confirmation['type']), ['jpg','jpeg','png']);
@endphp

<style>
    /* wizard */
    .wizard-steps{display:flex;border-radius:10px;overflow:hidden;margin-bottom:28px;box-shadow:0 2px 8px rgba(27,42,74,.08);}
    .wizard-step{flex:1;padding:13px 10px;text-align:center;font-size:13px;font-weight:600;background:var(--cream2);color:var(--muted);}
    .wizard-step.done{background:var(--gold);color:var(--navy);}
    .wizard-step.active{background:var(--navy);color:#fff;}
    .wizard-step .step-num{display:block;font-size:18px;font-family:'Playfair Display',serif;margin-bottom:2px;}
    .wizard-step .step-label{font-size:11px;text-transform:uppercase;letter-spacing:.4px;}

    /* success banner */
    .s-banner{
        display:flex;align-items:center;gap:14px;
        background:#e8f5ee;border:1px solid #bddecb;border-radius:10px;
        padding:14px 18px;margin-bottom:28px;font-size:14px;color:#1a5c32;
        animation:fadeDown .4s ease;
    }
    .s-banner strong{font-size:14px;}

    /* main grid */
    .s-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:24px;
        align-items:start;
        max-width:1000px;
        margin:0 auto;
    }
    @media(max-width:880px){.s-grid{grid-template-columns:1fr;}}

    /* panel */
    .s-panel{
        border:1px solid var(--cream2);
        border-radius:12px;
        overflow:hidden;
        background:#fff;
    }
    .s-panel-head{
        display:flex;align-items:center;gap:8px;
        padding:13px 18px;
        background:var(--cream);
        border-bottom:1px solid var(--cream2);
        font-size:13px;font-weight:700;
        text-transform:uppercase;letter-spacing:.6px;color:var(--navy);
    }

    /* detail rows */
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

    /* file panel */
    .f-body{padding:24px 20px;text-align:center;}

    /* PDF */
    .pdf-icon{
        width:76px;height:76px;
        background:linear-gradient(135deg,#ff5252,#c62828);
        border-radius:14px;
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 14px;
        box-shadow:0 6px 20px rgba(198,40,40,.30);
    }
    .pdf-icon svg{width:36px;height:36px;fill:#fff;}
    .f-name{
        font-size:13px;font-weight:700;color:var(--navy);
        word-break:break-all;margin-bottom:14px;
    }
    .f-chip{
        display:inline-flex;align-items:center;gap:5px;
        background:#fde9e7;color:#b3261e;
        font-size:11px;font-weight:700;
        padding:4px 12px;border-radius:20px;margin-bottom:14px;
    }
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

    /* Image */
    .img-preview{
        border-radius:10px;overflow:hidden;
        border:1px solid var(--cream2);
        margin-bottom:14px;
        box-shadow:0 2px 12px rgba(0,0,0,.08);
    }
    .img-preview img{width:100%;display:block;max-height:240px;object-fit:cover;}
    .btn-view{
        display:flex;align-items:center;justify-content:center;gap:8px;
        padding:11px 18px;width:100%;
        background:var(--navy);color:#fff;
        font-size:14px;font-weight:700;border-radius:8px;
        text-decoration:none;margin-bottom:8px;
        transition:background .2s;font-family:'Inter',sans-serif;
    }
    .btn-view:hover{background:#243560;}
    .btn-dl-img{
        display:flex;align-items:center;justify-content:center;gap:8px;
        padding:11px 18px;width:100%;
        background:var(--cream);color:var(--navy);
        font-size:14px;font-weight:600;border-radius:8px;
        text-decoration:none;border:1.5px solid var(--cream2);
        transition:background .2s,border-color .2s;font-family:'Inter',sans-serif;
    }
    .btn-dl-img:hover{background:var(--cream2);border-color:var(--gold);}

    .f-note{
        margin-top:12px;font-size:12px;color:var(--muted);
        background:var(--cream);border-radius:8px;
        padding:10px 12px;text-align:left;
        display:flex;align-items:flex-start;gap:6px;
    }

    /* actions */
    .s-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px;}
</style>

<div style="max-width:900px;margin:0 auto;">

    {{-- Wizard --}}
    <div class="wizard-steps">
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Welcome</span></div>
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Details</span></div>
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Confirmation</span></div>
        <div class="wizard-step active"><span class="step-num">4</span><span class="step-label">Summary</span></div>
    </div>

    {{-- Success banner --}}
    <div class="s-banner">
        <span style="font-size:20px;">✅</span>
        <div>
            <strong>Booking Complete!</strong>
            Your reservation has been successfully processed. Keep your Booking ID for reference.
        </div>
    </div>

    <div class="s-grid">

        {{-- LEFT: Booking Details --}}
        <div class="s-panel">
            <div class="s-panel-head">📋 &nbsp;Booking Details</div>

            <div class="d-row">
                <div class="d-ico">👤</div>
                <div class="d-lbl">Customer Name</div>
                <div class="d-val">{{ $customerName }}</div>
            </div>
            <div class="d-row">
                <div class="d-ico">#</div>
                <div class="d-lbl">Booking ID</div>
                <div class="d-val"><span class="bk-chip">{{ $bookingId }}</span></div>
            </div>
            <div class="d-row">
                <div class="d-ico">📅</div>
                <div class="d-lbl">Check In Date</div>
                <div class="d-val">{{ \Carbon\Carbon::parse($details['check_in_date'])->format('F j, Y') }}</div>
            </div>
            <div class="d-row">
                <div class="d-ico">📅</div>
                <div class="d-lbl">Check Out Date</div>
                <div class="d-val">{{ \Carbon\Carbon::parse($details['check_out_date'])->format('F j, Y') }}</div>
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
                    {{ $details['room_name'] ?? $details['event_name'] }}
                    @if(isset($details['category_name']))
                        <div style="font-size:12px;color:var(--muted);font-weight:400;">{{ $details['category_name'] }}</div>
                    @endif
                </div>
            </div>
            @if(isset($details['price_per_night']))
            <div class="d-row">
                <div class="d-ico">💰</div>
                <div class="d-lbl">Rate</div>
                <div class="d-val">₱{{ number_format($details['price_per_night'], 2) }} / night</div>
            </div>
            @endif
            <div class="d-row">
                <div class="d-ico">👥</div>
                <div class="d-lbl">Number of Guests</div>
                <div class="d-val">{{ $details['number_of_persons'] }} {{ $details['number_of_persons'] == 1 ? 'guest' : 'guests' }}</div>
            </div>
        </div>

        {{-- RIGHT: Confirmation File --}}
        <div class="s-panel">
            <div class="s-panel-head">📎 &nbsp;Confirmation File</div>
            <div class="f-body">

                @if($isPdf)
                    <div class="pdf-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
                    </div>
                    <div class="f-chip">📄 PDF File</div>
                    <div class="f-name">{{ $confirmation['original_name'] }}</div>
                    <a href="{{ $viewUrl }}" target="_blank" class="btn-dl-pdf">
                        🔍 &nbsp;View PDF
                    </a>
                    <a href="{{ $downloadUrl }}" class="btn-dl-pdf" style="margin-top:10px;">
                        ⬇ &nbsp;Download PDF
                    </a>
                    <div class="f-note">
                        ℹ️ &nbsp;File uploaded for your booking:
                        <em>{{ $confirmation['original_name'] }}</em>
                    </div>

                @elseif($isImage)
                    <div class="img-preview">
                        <img src="{{ $viewUrl }}" alt="Confirmation image">
                    </div>
                    <div class="f-name">{{ $confirmation['original_name'] }}</div>
                    <a href="{{ $viewUrl }}" target="_blank" class="btn-view">
                        🔍 &nbsp;View Full Image
                    </a>
                    <a href="{{ $downloadUrl }}" class="btn-dl-img">
                        ⬇ &nbsp;Download Image
                    </a>
                    <div class="f-note">
                        ℹ️ &nbsp;File uploaded for your booking:
                        <em>{{ $confirmation['original_name'] }}</em>
                    </div>
                @endif

            </div>
        </div>

    </div>

    {{-- Actions --}}
    <div class="s-actions">
        <a href="{{ route('booking.reset') }}" class="btn btn-gold">✦ New Booking</a>
        <a href="{{ route('home') }}" class="btn btn-outline">Back to Home</a>
    </div>

</div>
@endsection
