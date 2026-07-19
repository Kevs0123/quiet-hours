@extends('layouts.app')
@section('title', 'Payment')
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
    .booking-hero-content { position: relative; z-index: 1; max-width: 760px; }
    .booking-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.16); border: 1px solid rgba(255,255,255,0.25);
        border-radius: 999px; padding: 6px 12px; font-size: 11px; font-weight: 700;
        letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;
    }
    .booking-hero h1 { margin: 0 0 8px; font-size: 28px; color: #fff; }
    .booking-hero p { margin: 0; color: rgba(255,255,255,0.86); line-height: 1.6; font-size: 14px; }
    .booking-shell {
        max-width: 1000px; margin: 0 auto; padding: 24px; border-radius: 20px;
        background: #fff; box-shadow: 0 10px 30px rgba(27,42,74,0.05);
    }
    .booking-intro { margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid #efe7da; }
    .booking-intro h2 { margin: 0 0 6px; color: #1b2a4a; font-size: 22px; }
    .booking-intro p { margin: 0; color: #6b6860; font-size: 14px; }

    .booking-recap {
        background: #fdf6ec; border: 1px solid #ead7ab; border-radius: 14px;
        padding: 16px 18px; margin-bottom: 20px; color: #5b4c25;
    }
    .booking-recap-title {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: #a6833e; margin-bottom: 10px;
    }
    .booking-recap-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px 12px; font-size:13px; }

    .pay-total {
        display: flex; align-items: center; justify-content: space-between;
        background: var(--navy); color: #fff; border-radius: 14px;
        padding: 18px 22px; margin-bottom: 22px;
    }
    .pay-total .label { font-size: 12px; text-transform: uppercase; letter-spacing: .6px; color: rgba(255,255,255,.7); }
    .pay-total .amount { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--gold); }

    .method-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 22px;
    }
    @media (max-width: 720px) { .method-grid { grid-template-columns: repeat(2, 1fr); } }
    .method-card {
        border: 1.5px solid #d6d0c2; border-radius: 12px; padding: 16px 10px;
        text-align: center; cursor: pointer; transition: border-color .2s, background .2s, transform .2s;
        position: relative; background: #fff;
    }
    .method-card:hover { border-color: var(--gold); transform: translateY(-1px); }
    .method-card.selected { border-color: var(--gold); background: #fdf9f1; box-shadow: 0 0 0 3px rgba(201,161,90,.18); }
    .method-icon { font-size: 26px; margin-bottom: 6px; }
    .method-name { font-size: 13px; font-weight: 600; color: var(--navy); }
    .method-card input { position: absolute; opacity: 0; pointer-events: none; }

    .pay-note {
        display: flex; gap: 10px; align-items: flex-start; background: var(--cream);
        border-radius: 10px; padding: 12px 14px; margin-bottom: 20px; font-size: 12.5px; color: var(--muted);
    }
    .pay-hint {
        margin-top: 14px; padding: 14px 16px; border-radius: 12px;
        background: #fdf9f1; border: 1px solid #ead7ab;
        animation: fadeUp .25s ease;
    }
    .pay-hint-title { font-weight: 700; color: var(--navy); font-size: 13.5px; margin-bottom: 8px; }
    .pay-hint-row { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; color: var(--ink); }
    .pay-hint-row span { color: var(--muted); }
    .pay-hint-note { margin-top: 8px; font-size: 12px; color: var(--muted); line-height: 1.5; }

    @media (max-width: 768px) {
        .booking-hero { padding: 22px; }
        .booking-shell { padding: 18px; }
        .booking-recap-grid { grid-template-columns:1fr; }
        .pay-total { flex-direction: column; align-items: flex-start; gap: 6px; }
    }
</style>

<div class="booking-hero">
    <div class="booking-hero-content">
        <div class="booking-pill">Step 2 of 4</div>
        <h1>Complete your payment</h1>
        <p>Choose a payment method and enter your reference number. Your reservation is finalized once an admin confirms your payment.</p>
    </div>
</div>

<div class="card booking-shell">
    <div class="wizard-steps">
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Welcome</span></div>
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Details</span></div>
        <div class="wizard-step active"><span class="step-num">2</span><span class="step-label">Payment</span></div>
        <div class="wizard-step"><span class="step-num">3</span><span class="step-label">Summary</span></div>
    </div>

    <div class="booking-intro">
        <h2>Payment details</h2>
        <p>This is a simulated payment step for demo purposes — no real transaction is processed.</p>
    </div>

    @if ($details)
    <div class="booking-recap">
        <div class="booking-recap-title">Booking recap</div>
        <div class="booking-recap-grid">
            <div><span style="color:var(--muted);">Customer:</span> <strong>{{ $customerName }}</strong></div>
            <div><span style="color:var(--muted);">Booking ID:</span>
                <code style="background:#fff;padding:1px 7px;border-radius:4px;font-weight:700;letter-spacing:1px;">{{ $bookingId }}</code>
            </div>
            <div><span style="color:var(--muted);">Room:</span> <strong>{{ $details['room_name'] ?? $details['event_name'] }}</strong></div>
            <div><span style="color:var(--muted);">Category:</span> <strong>{{ $details['category_name'] ?? '—' }}</strong></div>
            <div><span style="color:var(--muted);">Check-in:</span> <strong>{{ \Carbon\Carbon::parse($details['check_in_date'])->format('M j, Y') }}</strong></div>
            <div><span style="color:var(--muted);">Check-out:</span> <strong>{{ \Carbon\Carbon::parse($details['check_out_date'])->format('M j, Y') }}</strong></div>
            <div><span style="color:var(--muted);">Persons:</span> <strong>{{ $details['number_of_persons'] }}</strong></div>
            <div><span style="color:var(--muted);">Nights:</span> <strong>{{ $nights }}</strong></div>
        </div>
    </div>
    @endif

    @if($total)
    <div class="pay-total">
        <div>
            <div class="label">Total Amount Due</div>
            <div style="font-size:12px;color:rgba(255,255,255,.6);margin-top:2px;">
                ₱{{ number_format($details['price_per_night'], 2) }} &times; {{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}
            </div>
        </div>
        <div class="amount">₱{{ number_format($total, 2) }}</div>
    </div>
    @endif

    <form action="{{ route('booking.payment.store') }}" method="POST" novalidate>
        @csrf

        <div class="field">
            <label>Payment Method <span style="color:var(--danger);">*</span></label>
            <input type="hidden" name="payment_method" id="payment_method_hidden" value="{{ old('payment_method') }}">
            <div class="method-grid">
                <div class="method-card @if(old('payment_method')==='gcash') selected @endif" data-method="gcash" onclick="selectMethod(this)">
                    <div class="method-icon">📱</div>
                    <div class="method-name">GCash</div>
                </div>
                <div class="method-card @if(old('payment_method')==='paymaya') selected @endif" data-method="paymaya" onclick="selectMethod(this)">
                    <div class="method-icon">💳</div>
                    <div class="method-name">Maya</div>
                </div>
                <div class="method-card @if(old('payment_method')==='bank_transfer') selected @endif" data-method="bank_transfer" onclick="selectMethod(this)">
                    <div class="method-icon">🏦</div>
                    <div class="method-name">Bank Transfer</div>
                </div>
                <div class="method-card @if(old('payment_method')==='credit_card') selected @endif" data-method="credit_card" onclick="selectMethod(this)">
                    <div class="method-icon">💰</div>
                    <div class="method-name">Credit / Debit</div>
                </div>
            </div>
            @error('payment_method')
                <span class="error-text">{{ $message }}</span>
            @enderror

            <div id="pay-hint-gcash" class="pay-hint" style="display:none;">
                <div class="pay-hint-title">📱 Send payment via GCash</div>
                <div class="pay-hint-row"><span>Send to</span><strong>0917 123 4567</strong></div>
                <div class="pay-hint-row"><span>Account name</span><strong>Quiet Hours Hotel</strong></div>
                <div class="pay-hint-note">After sending, copy the reference number shown in your GCash receipt into the field below.</div>
            </div>
            <div id="pay-hint-paymaya" class="pay-hint" style="display:none;">
                <div class="pay-hint-title">💳 Send payment via Maya</div>
                <div class="pay-hint-row"><span>Send to</span><strong>0917 123 4567</strong></div>
                <div class="pay-hint-row"><span>Account name</span><strong>Quiet Hours Hotel</strong></div>
                <div class="pay-hint-note">After sending, copy the reference number shown in your Maya receipt into the field below.</div>
            </div>
            <div id="pay-hint-bank_transfer" class="pay-hint" style="display:none;">
                <div class="pay-hint-title">🏦 Send payment via bank transfer</div>
                <div class="pay-hint-row"><span>Bank</span><strong>BDO</strong></div>
                <div class="pay-hint-row"><span>Account number</span><strong>0012 3456 7890</strong></div>
                <div class="pay-hint-row"><span>Account name</span><strong>Quiet Hours Hotel Corp.</strong></div>
                <div class="pay-hint-note">After sending, copy the transaction/trace number from your bank's confirmation into the field below.</div>
            </div>
            <div id="pay-hint-credit_card" class="pay-hint" style="display:none;">
                <div class="pay-hint-title">💰 Pay by credit / debit card</div>
                <div class="pay-hint-note">This is a demo booking flow, so no card form is collected here. Just enter any reference number (e.g. your last 4 card digits + date) so our team has something to match against your statement.</div>
            </div>
        </div>

        <div class="field">
            <label>Payment Reference / Transaction Number <span style="color:var(--danger);">*</span></label>
            <input type="text" name="payment_reference" value="{{ old('payment_reference') }}"
                   placeholder="e.g. GC-2026-4471"
                   maxlength="40"
                   @class(['is-invalid' => $errors->has('payment_reference')])>
            @error('payment_reference')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="pay-note">
            ℹ️ <div>Enter the reference/transaction number from your GCash, Maya, bank transfer, or card receipt. An admin will verify this and confirm your booking shortly — you'll be notified by email either way.</div>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('booking.details') }}" class="btn btn-outline">&larr; Back</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Submit Payment &rarr;
            </button>
        </div>
    </form>
</div>

<script>
    function selectMethod(card) {
        document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        document.getElementById('payment_method_hidden').value = card.dataset.method;

        document.querySelectorAll('.pay-hint').forEach(h => h.style.display = 'none');
        const hint = document.getElementById('pay-hint-' + card.dataset.method);
        if (hint) hint.style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const preselected = document.querySelector('.method-card.selected');
        if (preselected) selectMethod(preselected);
    });

    document.querySelector('form')?.addEventListener('submit', function (e) {
        const field = document.getElementById('payment_method_hidden');
        if (!field.value) {
            e.preventDefault();
            let msg = document.getElementById('method-error');
            if (!msg) {
                msg = document.createElement('span');
                msg.id = 'method-error';
                msg.className = 'error-text';
                msg.textContent = 'Please select a payment method.';
                document.querySelector('.method-grid').insertAdjacentElement('afterend', msg);
            }
            document.querySelector('.method-grid').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>

@endsection
