@extends('layouts.app')
@section('title', 'Booking Details')
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
        background: radial-gradient(circle at top right, rgba(201,161,90,0.28), transparent 35%);
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
        margin-bottom: 20px;
        padding-bottom: 16px;
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
    .booking-tip {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        background: #fdf6ec;
        border: 1px solid #ead7ab;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 18px;
        color: #5b4c25;
        font-size: 13px;
    }
    .booking-tip strong { color: #1b2a4a; }
    .room-picker-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:14px; margin-bottom:6px; }
    .room-card { position:relative; border:2px solid var(--cream2); border-radius:12px; overflow:hidden; cursor:pointer; background:#fff; transition:border-color .2s, transform .15s, box-shadow .2s; }
    .room-card:hover { border-color:var(--gold); transform:translateY(-2px); box-shadow:0 10px 24px rgba(27,42,74,0.06); }
    .room-card.selected { border-color:var(--gold); box-shadow:0 0 0 2px rgba(201,161,90,.35); }
    .room-card img { width:100%; height:100px; object-fit:cover; display:block; background:var(--cream2); }
    .room-card-body { padding:10px 12px; }
    .room-card-check { position:absolute; top:8px; right:8px; width:24px; height:24px; border-radius:50%; background:var(--gold); color:var(--navy); font-size:13px; font-weight:700; display:none; align-items:center; justify-content:center; }
    .room-card.selected .room-card-check { display:flex; }
    @media (max-width: 768px) {
        .booking-hero { padding: 22px; }
        .booking-shell { padding: 18px; }
        .room-picker-grid { grid-template-columns:1fr 1fr; }
    }
    @media (max-width: 480px) {
        .room-picker-grid { grid-template-columns:1fr; }
    }
</style>

<div class="booking-hero">
    <div class="booking-hero-content">
        <div class="booking-pill">Step 1 of 4</div>
        <h1>Choose your room and stay details</h1>
        <p>Pick a room, set your dates, and tell us how many guests will be joining you.</p>
    </div>
</div>

<div class="card booking-shell">
    <div class="wizard-steps">
        <div class="wizard-step done"><span class="step-num">✓</span><span class="step-label">Welcome</span></div>
        <div class="wizard-step active"><span class="step-num">2</span><span class="step-label">Details</span></div>
        <div class="wizard-step"><span class="step-num">3</span><span class="step-label">Payment</span></div>
        <div class="wizard-step"><span class="step-num">4</span><span class="step-label">Summary</span></div>
    </div>

    <div class="booking-intro">
        <h2>Room & stay details</h2>
        <p>Everything you need is on this page. Select a room, choose your dates, and continue when you are ready.</p>
    </div>

    <div class="booking-tip">
        <span style="font-size:16px;">💡</span>
        <div><strong>Helpful tip:</strong> Once you pick a room, the available dates update automatically and the form stays easy to follow.</div>
    </div>

    <form action="{{ route('booking.details.store') }}" method="POST" novalidate>
        @csrf

        <div class="field">
            <label>Customer Name</label>
            <input type="text" value="{{ $customerName }}" readonly>
        </div>

        <div class="field">
            <label>Booking Reference ID
                <span style="font-weight:400;text-transform:none;font-size:12px;color:var(--muted);">(auto-generated)</span>
            </label>
            <div style="display:flex;align-items:center;gap:10px;">
                <input type="text" value="{{ $bookingId }}" readonly
                       style="font-family:monospace;font-size:16px;font-weight:700;letter-spacing:2px;color:var(--navy);">
                <span style="font-size:20px;">🔑</span>
            </div>
        </div>

        <div class="field">
            <label>Select Room <span style="color:var(--danger);">*</span></label>

            <input type="hidden" name="room_id" id="room_select_hidden"
                   value="{{ old('room_id', $savedDetails['room_id'] ?? '') }}">

            @forelse ($categories as $category)
                <h3 style="margin-top:18px;margin-bottom:10px;">{{ $category->name }}</h3>
                <div class="room-picker-grid">
                    @foreach ($category->rooms as $room)
                        <div class="room-card"
                             data-room-id="{{ $room->id }}"
                             data-capacity="{{ $room->capacity }}"
                             data-price="{{ number_format($room->price_per_night, 2) }}"
                             data-desc="{{ $room->description }}"
                             @class(['selected' => (string) old('room_id', $savedDetails['room_id'] ?? '') === (string) $room->id])
                             onclick="selectRoomCard(this)">
                               <img src="{{ $room->image_url ?? 'https://via.placeholder.com/800x600?text=No+Photo' }}"
                                   onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Photo'"
                                   alt="{{ $room->name }}" loading="lazy">
                            <div class="room-card-body">
                                <strong>{{ $room->name }}</strong>
                                <div style="font-size:13px;color:var(--gold2);font-weight:600;">₱{{ number_format($room->price_per_night, 2) }}/night</div>
                                <div style="font-size:12px;color:var(--muted);">max {{ $room->capacity }} guests</div>
                            </div>
                            <div class="room-card-check">✓</div>
                        </div>
                    @endforeach
                </div>
            @empty
                <p style="margin-top:10px;">No rooms are available right now.</p>
            @endforelse

            @error('room_id')
                <span class="error-text">{{ $message }}</span>
            @enderror

            <div id="room-info"
                 style="display:none;margin-top:14px;background:var(--cream);border-radius:8px;padding:12px 16px;font-size:13px;border-left:3px solid var(--gold);">
                <div style="display:flex;gap:20px;flex-wrap:wrap;">
                    <div>💰 <strong>Price:</strong> <span id="ri-price"></span>/night</div>
                    <div>👥 <strong>Max guests:</strong> <span id="ri-capacity"></span></div>
                </div>
                <div id="ri-desc" style="margin-top:6px;color:var(--muted);"></div>
            </div>
        </div>

        <div id="availability-legend" style="display:none;margin-bottom:10px;font-size:12px;color:var(--muted);background:var(--cream);border-radius:6px;padding:8px 12px;">
            🚫 Greyed-out / strikethrough dates on the calendar below are already booked for this room and can't be selected.
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="field">
                <label>Check-in Date <span style="color:var(--danger);">*</span>
                    <span style="font-weight:400;text-transform:none;font-size:11px;color:var(--muted);">(select via calendar)</span>
                </label>
                <input type="text"
                       name="check_in_date"
                       id="check_in_date"
                       placeholder="Click to select date"
                       value="{{ old('check_in_date', $savedDetails['check_in_date'] ?? now()->format('Y-m-d')) }}"
                       readonly
                       @class(['is-invalid' => $errors->has('check_in_date')])
                       style="cursor:pointer;background:#f8f9fa;">
                @error('check_in_date')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div class="field">
                <label>Check-out Date <span style="color:var(--danger);">*</span>
                    <span style="font-weight:400;text-transform:none;font-size:11px;color:var(--muted);">(select via calendar)</span>
                </label>
                <input type="text"
                       name="check_out_date"
                       id="check_out_date"
                       placeholder="Click to select date"
                       value="{{ old('check_out_date', $savedDetails['check_out_date'] ?? now()->addDay()->format('Y-m-d')) }}"
                       readonly
                       @class(['is-invalid' => $errors->has('check_out_date')])
                       style="cursor:pointer;background:#f8f9fa;">
                @error('check_out_date')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div id="duration-display"
             style="display:none;margin:-8px 0 18px;font-size:13px;color:var(--success);font-weight:600;">
            📅 <span id="duration-text"></span>
        </div>

        <div class="field">
            <label>Number of Persons <span style="color:var(--danger);">*</span>
                <span style="font-weight:400;text-transform:none;font-size:11px;color:var(--muted);">(min. 1)</span>
            </label>
            <input type="number"
                   name="number_of_persons"
                   id="number_of_persons"
                   min="1"
                   value="{{ old('number_of_persons', $savedDetails['number_of_persons'] ?? 1) }}"
                   @class(['is-invalid' => $errors->has('number_of_persons')])>
            @error('number_of_persons')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('booking.start', ['customerName' => $customerName]) }}"
               class="btn btn-outline">&larr; Back</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Continue to Payment &rarr;
            </button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Room info card + hidden input update on card click
    window.selectRoomCard = function(cardEl) {
        document.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
        cardEl.classList.add('selected');
        document.getElementById('room_select_hidden').value = cardEl.dataset.roomId;

        const card = document.getElementById('room-info');
        document.getElementById('ri-price').textContent    = '₱' + cardEl.dataset.price;
        document.getElementById('ri-capacity').textContent = cardEl.dataset.capacity + ' guests';
        document.getElementById('ri-desc').textContent     = cardEl.dataset.desc || '';
        card.style.display = 'block';
        // enforce capacity on persons field
        const persons = document.getElementById('number_of_persons');
        if (persons) persons.max = cardEl.dataset.capacity;
        // fetch and apply this room's booked dates to both calendars
        loadRoomAvailability(cardEl.dataset.roomId);
    };

    // Trigger on load if a room is already selected (validation repopulate)
    const preselectedCard = document.querySelector('.room-card.selected');

    // Date interplay — checkout min follows checkin
    const checkIn  = document.getElementById('check_in_date');
    const checkOut = document.getElementById('check_out_date');
    const durBox   = document.getElementById('duration-display');
    const durText  = document.getElementById('duration-text');

    function updateDuration() {
        if (!checkIn.value || !checkOut.value) { durBox.style.display='none'; return; }
        const d1 = new Date(checkIn.value);
        const d2 = new Date(checkOut.value);
        const nights = Math.round((d2 - d1) / 86400000);
        if (nights > 0) {
            durText.textContent = nights + (nights === 1 ? ' night' : ' nights');
            durBox.style.display = 'block';
        } else {
            durBox.style.display = 'none';
        }
    }

    // Initialize Flatpickr for check-in date - calendar only
    const checkInPicker = flatpickr('#check_in_date', {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        enableTime: false,
        clickOpens: true,
        mode: 'single',
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutPicker.set('minDate', nextDay);
                checkOut.value = '';
                updateDuration();
            }
        }
    });

    // Initialize Flatpickr for check-out date - calendar only
    const checkOutPicker = flatpickr('#check_out_date', {
        minDate: new Date(new Date().setDate(new Date().getDate() + 1)),
        dateFormat: 'Y-m-d',
        enableTime: false,
        clickOpens: true,
        mode: 'single',
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                updateDuration();
            }
        }
    });

    // Fetch this room's existing bookings and grey them out on both calendars
    const legend = document.getElementById('availability-legend');

    window.loadRoomAvailability = function(roomId) {
        if (!roomId) return;

        fetch(`{{ url('/booking/room-availability') }}/${roomId}`, {
            headers: { 'Accept': 'application/json' },
        })
            .then(res => res.json())
            .then(data => {
                const disabledRanges = (data.booked || []).map(r => ({ from: r.from, to: r.to }));

                checkInPicker.set('disable', disabledRanges);
                checkOutPicker.set('disable', disabledRanges);

                if (legend) {
                    legend.style.display = disabledRanges.length ? 'block' : 'none';
                }
            })
            .catch(() => {
                // Fail silently — worst case the calendar just won't grey out
                // booked dates for this room, validation still catches conflicts.
            });
    };

    if (preselectedCard) {
        selectRoomCard(preselectedCard); // also triggers loadRoomAvailability once pickers exist
    }

    updateDuration(); // run on load

    // Guard: hidden inputs can't reliably use the native `required` attribute,
    // so block submission client-side too if no room card has been picked.
    document.querySelector('form').addEventListener('submit', function (e) {
        const roomIdField = document.getElementById('room_select_hidden');
        if (!roomIdField.value) {
            e.preventDefault();
            const grid = document.querySelector('.room-picker-grid');
            if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            let msg = document.getElementById('room-picker-error');
            if (!msg) {
                msg = document.createElement('span');
                msg.id = 'room-picker-error';
                msg.className = 'error-text';
                msg.textContent = 'Please select a room before continuing.';
                document.querySelector('.room-picker-grid')?.insertAdjacentElement('afterend', msg);
            }
        }
    });
});
</script>

@endsection
