@extends('layouts.app')
@section('title', 'Welcome, ' . $customerName)

@section('fullpage')
<style>
    .site-main { margin: 0; padding: 0; max-width: 100%; }

    .client-page {
        min-height: auto;
        display: grid;
        grid-template-columns: 1.2fr .8fr;
        gap: 0;
        background: linear-gradient(135deg, #fcfaf6 0%, #f5ebdc 100%);
    }

    .client-left {
        background: #fff;
        padding: 52px 48px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
        max-width: 900px;
        margin: 0 auto;
        width: 100%;
    }

    .client-right {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px;
        background: linear-gradient(145deg, #f4e8d2 0%, #faf6ee 100%);
    }

    .client-summary-card {
        width: 100%;
        max-width: 380px;
        background: rgba(255,255,255,0.95);
        border: 1px solid #eadfcf;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 16px 40px rgba(27,42,74,0.08);
    }

    .client-summary-card h2 {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        color: #1b2a4a;
        margin: 0 0 10px;
    }

    .client-summary-card p {
        font-size: 14px;
        line-height: 1.6;
        color: #6b6860;
        margin-bottom: 16px;
    }

    .summary-list {
        list-style: none;
        padding: 0;
        margin: 0 0 16px;
        display: grid;
        gap: 10px;
    }

    .summary-list li {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13px;
        color: #31415a;
        font-weight: 600;
    }

    .summary-list li::before {
        content: '•';
        color: #c9a15a;
        font-size: 18px;
        line-height: 1;
    }

    .client-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fdf6ec;
        border: 1px solid #e8c97a;
        color: #a6833e;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 5px 13px;
        border-radius: 20px;
        margin-bottom: 20px;
    }

    .client-left h1 {
        font-size: 34px;
        line-height: 1.15;
        color: #1b2a4a;
        margin-bottom: 10px;
    }
    .client-left h1 span { color: #c9a15a; }

    .client-left > p {
        font-size: 14.5px;
        color: #6b6860;
        line-height: 1.7;
        margin-bottom: 28px;
    }

    .step-list { margin-bottom: 32px; }

    .step-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 14px 0;
        border-bottom: 1px solid #f0ece4;
        animation: fadeUp .5s ease both;
    }
    .step-item:last-child { border-bottom: none; }
    .step-item:nth-child(2) { animation-delay: .07s; }
    .step-item:nth-child(3) { animation-delay: .14s; }
    .step-item:nth-child(4) { animation-delay: .21s; }

    .step-num {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #1b2a4a;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
    }
    .step-num.gold { background: #c9a15a; color: #1b2a4a; }

    .step-text h3 { font-size: 14px; color: #1b2a4a; margin-bottom: 3px; font-weight: 600; }
    .step-text p  { font-size: 12.5px; color: #6b6860; margin: 0; line-height: 1.5; }

    .btn-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 15px 24px;
        background: #c9a15a;
        color: #1b2a4a;
        font-size: 15px;
        font-weight: 700;
        border-radius: 9px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background .22s, transform .22s, box-shadow .22s;
        font-family: 'Inter', sans-serif;
        animation: fadeUp .5s ease .28s both;
    }
    .btn-cta:hover {
        background: #a6833e;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(201,161,90,.30);
    }

    @media (max-width: 860px) {
        .client-page { grid-template-columns: 1fr; }
        .client-left { padding: 36px 24px; }
        .client-right { padding: 24px; }
    }
</style>

<div class="client-page">

    <div class="client-left">
        <div class="client-badge">🏨 Quiet Hours Hotel</div>

        <h1>Welcome back,<br><span>{{ $customerName }}</span>!</h1>

        <p>Your comfort is our priority. Complete your booking in just a few steps below.</p>

        <div class="step-list">
            <div class="step-item">
                <div class="step-num gold">✓</div>
                <div class="step-text">
                    <h3>You're signed in</h3>
                    <p>Logged in as <strong>{{ auth()->user()->email ?? $customerName }}</strong>.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    <h3>Booking Details</h3>
                    <p>Your reference ID is auto-generated. Tell us the room or event, dates, and number of guests.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    <h3>Secure Payment</h3>
                    <p>Choose GCash, Maya, bank transfer, or card, and enter your reference number.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    <h3>Summary</h3>
                    <p>Review your booking — it's confirmed once our team verifies your payment.</p>
                </div>
            </div>
        </div>

        <a href="{{ route('booking.details') }}" class="btn-cta">
            Continue to Booking &rarr;
        </a>
    </div>

    <div class="client-right">
        <div class="client-summary-card">
            <h2>One smooth booking flow</h2>
            <p>From your first step to final confirmation, every part of the experience is simplified and easy to follow.</p>
            <ul class="summary-list">
                <li>Choose a room that fits your needs</li>
                <li>Pick your stay dates in seconds</li>
                <li>Upload confirmation in one place</li>
                <li>Review everything before you finish</li>
            </ul>
            <div class="home-highlight" style="margin-top: 6px;">Your reservation is always easy to review and track.</div>
        </div>
    </div>

</div>
@endsection

@section('content')
@endsection
