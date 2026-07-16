@extends('layouts.app')
@section('title', 'Welcome')

@section('fullpage')
<style>
    .site-main { margin: 0; padding: 0; max-width: 100%; }

    .welcome-hero {
        min-height: calc(100vh - 68px);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        overflow: hidden;
    }
    .welcome-hero-bg {
        position: absolute; inset: 0;
        background-image: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1800&q=85&auto=format&fit=crop');
        background-size: cover;
        background-position: center 55%;
        animation: slowZoom 18s ease-in-out infinite alternate;
    }
    @keyframes slowZoom {
        from { transform: scale(1.04); }
        to   { transform: scale(1.10); }
    }
    .welcome-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to bottom, rgba(10,18,40,.55) 0%, rgba(10,18,40,.45) 40%, rgba(10,18,40,.75) 100%);
    }
    .welcome-hero-content {
        position: relative; z-index: 2;
        padding: 60px 24px 120px;
        max-width: 740px; margin: 0 auto;
        animation: fadeUp .65s ease both;
    }
    .welcome-tagline {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(201,161,90,.20); border: 1px solid rgba(201,161,90,.50);
        color: #e8c97a; font-size: 12px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.5px; padding: 6px 16px; border-radius: 24px; margin-bottom: 24px;
    }
    .welcome-hero-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(36px,5vw,60px); color: #fff; line-height: 1.12;
        margin-bottom: 18px; text-shadow: 0 4px 24px rgba(0,0,0,.45);
    }
    .welcome-hero-content h1 em { font-style: normal; color: #e8c97a; }
    .welcome-hero-content > p {
        font-size: 17px; color: rgba(255,255,255,.82);
        line-height: 1.7; max-width: 520px; margin: 0 auto 36px;
    }
    .welcome-hero-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

    .btn-hero-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 14px 32px; background: #c9a15a; color: #1b2a4a;
        font-size: 15px; font-weight: 700; border-radius: 8px; text-decoration: none;
        transition: background .22s, transform .22s, box-shadow .22s; font-family: 'Inter', sans-serif;
    }
    .btn-hero-primary:hover { background: #a6833e; color: #fff; transform: translateY(-2px); box-shadow: 0 8px 28px rgba(201,161,90,.35); }

    .btn-hero-ghost {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 14px 32px; background: rgba(255,255,255,.12); color: #fff;
        font-size: 15px; font-weight: 600; border-radius: 8px; text-decoration: none;
        border: 1.5px solid rgba(255,255,255,.40); backdrop-filter: blur(6px);
        transition: background .22s, border-color .22s, transform .22s; font-family: 'Inter', sans-serif;
    }
    .btn-hero-ghost:hover { background: rgba(255,255,255,.22); border-color: rgba(255,255,255,.70); transform: translateY(-2px); }

    .hero-stats {
        position: absolute; bottom: 0; left: 0; right: 0; z-index: 2;
        display: flex; justify-content: center;
        background: rgba(10,18,40,.72); backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255,255,255,.10);
    }
    .hero-stat { flex: 1; max-width: 220px; text-align: center; padding: 18px 16px; border-right: 1px solid rgba(255,255,255,.10); }
    .hero-stat:last-child { border-right: none; }
    .hero-stat-num { font-family:'Playfair Display',serif; font-size:26px; font-weight:700; color:#e8c97a; display:block; }
    .hero-stat-label { font-size:11px; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.8px; margin-top:2px; }

    /* Rooms section */
    .rooms-section { background: var(--cream); padding: 80px 24px 90px; }
    .rooms-inner { max-width: 100%; margin: 0 auto; }
    .section-header { text-align: center; margin-bottom: 42px; }
    .section-eyebrow { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:var(--gold2); margin-bottom:10px; display:block; }
    .section-header h2 { font-family:'Playfair Display',serif; font-size:34px; color:var(--navy); margin-bottom:10px; }
    .section-header p { color:var(--muted); font-size:15px; max-width:480px; margin:0 auto; }

    .cat-tabs { display:flex; gap:8px; justify-content:center; flex-wrap:wrap; margin-bottom:40px; }
    .cat-tab { padding:8px 20px; border-radius:24px; font-size:13px; font-weight:600; cursor:pointer; border:1.5px solid #d6d0c2; background:#fff; color:var(--muted); transition:all .2s; user-select:none; }
    .cat-tab:hover { border-color:var(--navy); color:var(--navy); }
    .cat-tab.active { background:var(--navy); color:#fff; border-color:var(--navy); }

    .rooms-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:24px; }
    .room-card { background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 2px 16px rgba(27,42,74,.07); transition:transform .25s,box-shadow .25s; display:flex; flex-direction:column; }
    .room-card:hover { transform:translateY(-5px); box-shadow:0 12px 40px rgba(27,42,74,.14); }
    .room-card-img { height:200px; position:relative; overflow:hidden; }
    .room-card-img img { width:100%; height:100%; object-fit:cover; transition:transform .5s ease; }
    .room-card:hover .room-card-img img { transform:scale(1.06); }
    .room-card-badge { position:absolute; top:12px; left:12px; background:var(--success); color:#fff; font-size:11px; font-weight:700; padding:4px 10px; border-radius:6px; text-transform:uppercase; }
    .room-card-body { padding:20px 22px; flex:1; display:flex; flex-direction:column; }
    .room-card-category { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--gold2); margin-bottom:6px; }
    .room-card-name { font-family:'Playfair Display',serif; font-size:19px; color:var(--navy); margin-bottom:8px; }
    .room-card-desc { font-size:13px; color:var(--muted); line-height:1.6; margin-bottom:16px; flex:1; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .room-card-footer { display:flex; align-items:center; justify-content:space-between; padding-top:14px; border-top:1px solid var(--cream2); gap:10px; flex-wrap:wrap; }
    .room-card-price .amount { font-family:'Playfair Display',serif; font-size:22px; font-weight:700; color:var(--navy); line-height:1; }
    .room-card-price .per { font-size:11px; color:var(--muted); margin-top:2px; }
    .room-card-book { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; background:var(--gold); color:var(--navy); font-size:13px; font-weight:700; border-radius:7px; text-decoration:none; transition:background .2s,transform .2s; }
    .room-card-book:hover { background:var(--gold2); color:#fff; transform:translateY(-1px); }
    .rooms-empty { grid-column:1/-1; text-align:center; padding:48px; color:var(--muted); }
    .room-card-actions { display:flex; align-items:center; gap:8px; }
    .room-card-view { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:transparent; color:var(--navy); font-size:13px; font-weight:700; border-radius:7px; text-decoration:none; border:1.5px solid var(--navy); cursor:pointer; transition:background .2s,color .2s; }
    .room-card-view:hover { background:var(--navy); color:#fff; }

    /* Room details modal */
    .room-modal-overlay { display:none; position:fixed; inset:0; z-index:1000; background:rgba(10,18,40,.62); backdrop-filter:blur(3px); align-items:center; justify-content:center; padding:24px; }
    .room-modal-overlay.open { display:flex; animation:fadeUp .25s ease both; }
    .room-modal { background:#fff; border-radius:16px; max-width:640px; width:100%; max-height:88vh; overflow-y:auto; position:relative; box-shadow:0 24px 64px rgba(0,0,0,.35); }
    .room-modal-close { position:absolute; top:14px; right:14px; width:34px; height:34px; border-radius:50%; background:rgba(10,18,40,.55); color:#fff; border:none; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:2; transition:background .2s; }
    .room-modal-close:hover { background:var(--navy); }
    .room-modal-img { height:280px; overflow:hidden; }
    .room-modal-img img { width:100%; height:100%; object-fit:cover; }
    .room-modal-body { padding:26px 28px 30px; }
    .room-modal-category { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--gold2); margin-bottom:6px; }
    .room-modal-name { font-family:'Playfair Display',serif; font-size:26px; color:var(--navy); margin-bottom:12px; }
    .room-modal-desc { font-size:14px; color:var(--muted); line-height:1.7; margin-bottom:22px; }
    .room-modal-meta { display:flex; flex-wrap:wrap; gap:14px; margin-bottom:24px; }
    .room-modal-meta-item { flex:1; min-width:130px; background:var(--cream); border-radius:10px; padding:14px 16px; }
    .room-modal-meta-label { font-size:11px; text-transform:uppercase; letter-spacing:.6px; color:var(--muted); margin-bottom:4px; }
    .room-modal-meta-value { font-family:'Playfair Display',serif; font-size:18px; color:var(--navy); font-weight:700; }
    .room-modal-footer { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; padding-top:18px; border-top:1px solid var(--cream2); }

    /* Why section */
    .why-section { background:#fff; padding:80px 24px; }
    .why-inner { max-width: 100%; margin: 0 auto; }
    .why-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:22px; }
    .why-card { background:var(--cream); border-radius:14px; padding:30px 24px; text-align:center; border-bottom:3px solid transparent; transition:transform .25s,box-shadow .25s,border-color .25s; }
    .why-card:hover { transform:translateY(-4px); box-shadow:0 10px 32px rgba(27,42,74,.10); border-bottom-color:var(--gold); }
    .why-icon { width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#fdf6ec,#f5e9d0); display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto 14px; }
    .why-card h3 { font-size:16px; color:var(--navy); margin-bottom:8px; }
    .why-card p { font-size:13px; color:var(--muted); line-height:1.65; margin:0; }

    /* CTA */
    .cta-strip { background:linear-gradient(135deg,var(--navy) 0%,#243560 100%); padding:64px 24px; text-align:center; position:relative; overflow:hidden; }
    .cta-strip::before { content:''; position:absolute; top:-80px; right:-80px; width:320px; height:320px; background:rgba(201,161,90,.08); border-radius:50%; }
    .cta-strip h2 { font-family:'Playfair Display',serif; font-size:32px; color:#fff; margin-bottom:10px; }
    .cta-strip p { color:rgba(255,255,255,.72); font-size:15px; margin-bottom:28px; max-width:480px; margin-left:auto; margin-right:auto; }
    .cta-btns { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }

    @media(max-width:640px) {
        .hero-stats { display:none; }
        .welcome-hero-content h1 { font-size:34px; }
        .rooms-section,.why-section { padding:52px 16px; }
        .rooms-grid { grid-template-columns:1fr; }
    }
</style>

{{-- HERO --}}
<section class="welcome-hero">
    <div class="welcome-hero-bg"></div>
    <div class="welcome-hero-overlay"></div>
    <div class="welcome-hero-content">
        <div class="welcome-tagline">🏨 Quiet Hours Hotel</div>
        <h1>Experience the Art of<br><em>Quiet Luxury</em></h1>
        <p>A boutique hotel experience built for comfort, elegance, and memorable stays.</p>
        <div class="welcome-hero-btns">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-hero-primary">📊 Go to Dashboard</a>
                @else
                    <a href="{{ route('booking.home') }}" class="btn-hero-primary">✦ Book Your Stay</a>
                @endif
            @else
                <a href="{{ route('register') }}" class="btn-hero-primary">✦ Book Your Stay</a>
                <a href="{{ route('login') }}" class="btn-hero-ghost">Sign In</a>
            @endauth
        </div>
    </div>
    <div class="hero-stats">
        <div class="hero-stat"><span class="hero-stat-num">5★</span><div class="hero-stat-label">Luxury Rating</div></div>
        <div class="hero-stat"><span class="hero-stat-num">{{ $featuredRooms->count() }}+</span><div class="hero-stat-label">Premium Rooms</div></div>
        <div class="hero-stat"><span class="hero-stat-num">24/7</span><div class="hero-stat-label">Concierge Service</div></div>
        <div class="hero-stat"><span class="hero-stat-num">100%</span><div class="hero-stat-label">Secure Booking</div></div>
    </div>
</section>

{{-- ROOMS --}}
<section class="rooms-section" id="rooms">
    <div class="rooms-inner">
        <div class="section-header">
            <span class="section-eyebrow">Our Accommodations</span>
            <h2>Browse Available Rooms</h2>
            <p>Choose from our handpicked selection of rooms and suites.</p>
        </div>

        @if($categories->count() > 1)
        <div class="cat-tabs" id="catTabs">
            <div class="cat-tab active" data-cat="all">All Rooms</div>
            @foreach($categories as $cat)
                <div class="cat-tab" data-cat="{{ $cat->id }}">{{ $cat->name }}</div>
            @endforeach
        </div>
        @endif

        <div class="rooms-grid" id="roomsGrid">
            @forelse($featuredRooms as $i => $room)
            @php
                $imgs = [
                    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600&q=80&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600&q=80&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=600&q=80&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&q=80&auto=format&fit=crop',
                ];
            @endphp
            <div class="room-card" data-cat="{{ $room->room_category_id }}" style="animation:fadeUp .5s ease {{ $i*0.07 }}s both;"
                data-room-img="{{ $room->image_path ? $room->image_url : $imgs[$i % count($imgs)] }}"
                data-room-category="{{ $room->category->name }}"
                data-room-name="{{ $room->name }}"
                data-room-desc="{{ $room->description ?: 'A beautifully appointed room designed for your comfort and relaxation.' }}"
                data-room-price="₱{{ number_format($room->price_per_night, 0) }}"
                data-room-capacity="{{ $room->capacity }} guest{{ $room->capacity > 1 ? 's' : '' }}"
                data-room-book="{{ auth()->check() ? (auth()->user()->isClient() ? route('booking.home') : route('admin.rooms.show', $room)) : route('register') }}">
                <div class="room-card-img">
                    <img src="{{ $room->image_path ? $room->image_url : $imgs[$i % count($imgs)] }}" alt="{{ $room->name }}" loading="lazy">
                    <div class="room-card-badge">Available</div>
                </div>
                <div class="room-card-body">
                    <div class="room-card-category">{{ $room->category->name }}</div>
                    <div class="room-card-name">{{ $room->name }}</div>
                    <div class="room-card-desc">{{ $room->description ?: 'A beautifully appointed room designed for your comfort and relaxation.' }}</div>
                    <div class="room-card-footer">
                        <div>
                            <div class="room-card-price">
                                <span class="amount">₱{{ number_format($room->price_per_night, 0) }}</span>
                                <span class="per">per night</span>
                            </div>
                            <div style="font-size:12px;color:var(--muted);margin-top:4px;">👥 {{ $room->capacity }} guests</div>
                        </div>
                        <div class="room-card-actions">
                            <button type="button" class="room-card-view" data-room-details-btn>Details</button>
                            @auth
                                @if(auth()->user()->isClient())
                                    <a href="{{ route('booking.home') }}" class="room-card-book">Book Now</a>
                                @else
                                    <a href="{{ route('admin.rooms.show', $room) }}" class="room-card-book">Edit</a>
                                @endif
                            @else
                                <a href="{{ route('register') }}" class="room-card-book">Book Now</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="rooms-empty">
                <div style="font-size:48px;margin-bottom:12px;">🛏</div>
                <p>No rooms available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- WHY US --}}
<section class="why-section">
    <div class="why-inner">
        <div class="section-header" style="margin-bottom:36px;">
            <span class="section-eyebrow">Why Choose Us</span>
            <h2>The Quiet Hours Experience</h2>
            <p>Everything you need for a perfect stay.</p>
        </div>
        <div class="why-grid">
            <div class="why-card"><div class="why-icon">🛏</div><h3>Curated Rooms</h3><p>From standard comfort to presidential suites, every room is designed with care.</p></div>
            <div class="why-card"><div class="why-icon">📋</div><h3>Easy Booking</h3><p>Select your room, enter dates, upload confirmation — done in minutes.</p></div>
            <div class="why-card"><div class="why-icon">🔐</div><h3>Secure & Reliable</h3><p>Auto-generated booking IDs and encrypted file storage keep your reservation safe.</p></div>
        </div>
    </div>
</section>

{{-- ROOM DETAILS MODAL --}}
<div class="room-modal-overlay" id="roomModalOverlay">
    <div class="room-modal">
        <button type="button" class="room-modal-close" id="roomModalClose" aria-label="Close">✕</button>
        <div class="room-modal-img"><img id="roomModalImg" src="" alt=""></div>
        <div class="room-modal-body">
            <div class="room-modal-category" id="roomModalCategory"></div>
            <div class="room-modal-name" id="roomModalName"></div>
            <div class="room-modal-desc" id="roomModalDesc"></div>
            <div class="room-modal-meta">
                <div class="room-modal-meta-item">
                    <div class="room-modal-meta-label">Price / Night</div>
                    <div class="room-modal-meta-value" id="roomModalPrice"></div>
                </div>
                <div class="room-modal-meta-item">
                    <div class="room-modal-meta-label">Capacity</div>
                    <div class="room-modal-meta-value" id="roomModalCapacity"></div>
                </div>
                <div class="room-modal-meta-item">
                    <div class="room-modal-meta-label">Status</div>
                    <div class="room-modal-meta-value" style="color:var(--success);">Available</div>
                </div>
            </div>
            <div class="room-modal-footer">
                <span style="font-size:12px;color:var(--muted);">Handpicked for comfort and relaxation.</span>
                <a href="#" id="roomModalBook" class="room-card-book">Continue</a>
            </div>
        </div>
    </div>
</div>

{{-- CTA --}}
@guest
<section class="cta-strip">
    <h2>Ready for Your Stay?</h2>
    <p>Create a free account and book your perfect room in just a few minutes.</p>
    <div class="cta-btns">
        <a href="{{ route('register') }}" class="btn-hero-primary">✦ Create Free Account</a>
        <a href="{{ route('login') }}" class="btn-hero-ghost">Already have one? Sign In</a>
    </div>
</section>
@endguest

{{-- FOOTER --}}
<footer class="hotel-footer">
    <div class="footer-container">
        <div class="footer-grid">
            {{-- Brand & Description --}}
            <div class="footer-col">
                <div class="footer-brand">
                    <div class="footer-logo">🏨</div>
                    <h3>Quiet Hours Hotel</h3>
                </div>
                <p class="footer-desc">Experience the perfect blend of luxury, comfort, and tranquility. Our handpicked rooms and world-class service ensure your stay is nothing short of exceptional.</p>
                <div class="footer-rating">
                    <span class="stars">★★★★★</span>
                    <span class="rating-text">4.9/5 from 200+ Reviews</span>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="footer-col">
                <h4>Get In Touch</h4>
                <div class="footer-contact">
                    <div class="contact-item">
                        <span class="icon">📞</span>
                        <div>
                            <div class="label">Call Us</div>
                            <a href="tel:+1234567890">(123) 456-7890</a>
                        </div>
                    </div>
                    <div class="contact-item">
                        <span class="icon">📧</span>
                        <div>
                            <div class="label">Email</div>
                            <a href="mailto:info@quiethours.com">info@quiethours.com</a>
                        </div>
                    </div>
                    <div class="contact-item">
                        <span class="icon">📍</span>
                        <div>
                            <div class="label">Address</div>
                            <span>123 Mountain View Dr, Alpine City, MC 12345</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operating Hours --}}
            <div class="footer-col">
                <h4>Operating Hours</h4>
                <div class="footer-hours">
                    <div class="hour-row">
                        <span class="day">Monday - Friday</span>
                        <span class="time">24/7 Open</span>
                    </div>
                    <div class="hour-row">
                        <span class="day">Saturday - Sunday</span>
                        <span class="time">24/7 Open</span>
                    </div>
                    <div class="hour-row">
                        <span class="day">Check-in</span>
                        <span class="time">2:00 PM</span>
                    </div>
                    <div class="hour-row">
                        <span class="day">Check-out</span>
                        <span class="time">11:00 AM</span>
                    </div>
                </div>
            </div>

            {{-- Amenities & Services --}}
            <div class="footer-col">
                <h4>Amenities & Services</h4>
                <ul class="footer-list">
                    <li>✓ Free WiFi Throughout</li>
                    <li>✓ 24/7 Concierge Service</li>
                    <li>✓ Room Service Available</li>
                    <li>✓ Housekeeping Daily</li>
                    <li>✓ Fitness Center</li>
                    <li>✓ Business Lounge</li>
                </ul>
            </div>

            {{-- Quick Links --}}
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-list">
                    <li><a href="#rooms">Browse Rooms</a></li>
                    <li><a href="#rooms">View Categories</a></li>
                    <li><a href="{{ route('register') }}">Make a Booking</a></li>
                    <li><a href="{{ route('login') }}">Manage Booking</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            {{-- Social Media --}}
            <div class="footer-col">
                <h4>Follow Us</h4>
                <div class="footer-social">
                    <a href="#" title="Facebook" class="social-link">f</a>
                    <a href="#" title="Instagram" class="social-link">📷</a>
                    <a href="#" title="Twitter" class="social-link">𝕏</a>
                    <a href="#" title="LinkedIn" class="social-link">in</a>
                </div>
                <p class="footer-tagline">Join thousands of satisfied guests</p>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="footer-bottom">
            <div class="footer-copyright">
                &copy; {{ date('Y') }} <strong>Quiet Hours Hotel</strong> &mdash; A calm stay, every time. All rights reserved.
            </div>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <span class="divider">|</span>
                <a href="#">Terms of Use</a>
                <span class="divider">|</span>
                <a href="#">Sitemap</a>
                <span class="divider">|</span>
                <a href="#">Contact</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Enhanced Footer Styles */
    .hotel-footer {
        background: linear-gradient(135deg, var(--navy) 0%, #243560 100%);
        color: rgba(255,255,255,.85);
        padding: 80px 24px 40px;
        margin-top: 120px;
    }
    .footer-container { max-width: 100%; margin: 0 auto; }
    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 40px;
        margin-bottom: 50px;
    }
    .footer-col { }
    .footer-col h4 {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 18px;
        color: var(--gold);
    }

    {{-- Brand Section --}}
    .footer-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }
    .footer-logo {
        font-size: 32px;
        width: 48px;
        height: 48px;
        background: rgba(201,161,90,.15);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .footer-brand h3 {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        margin: 0;
        color: #fff;
    }
    .footer-desc {
        font-size: 13px;
        line-height: 1.65;
        margin-bottom: 16px;
        color: rgba(255,255,255,.75);
    }
    .footer-rating {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
    }
    .stars { font-size: 14px; color: var(--gold); letter-spacing: 2px; }
    .rating-text { color: rgba(255,255,255,.65); }

    {{-- Contact Section --}}
    .footer-contact { display: flex; flex-direction: column; gap: 16px; }
    .contact-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        font-size: 13px;
    }
    .contact-item .icon {
        font-size: 18px;
        width: 24px;
        text-align: center;
        flex-shrink: 0;
    }
    .contact-item .label {
        font-size: 11px;
        color: var(--gold);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 2px;
        font-weight: 600;
    }
    .contact-item a {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: color var(--trans);
    }
    .contact-item a:hover { color: var(--gold); }
    .contact-item span {
        color: rgba(255,255,255,.85);
    }

    {{-- Hours Section --}}
    .footer-hours { display: flex; flex-direction: column; gap: 12px; }
    .hour-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        background: rgba(255,255,255,.05);
        border-radius: 6px;
        font-size: 13px;
    }
    .hour-row .day { color: var(--gold); font-weight: 600; }
    .hour-row .time { color: rgba(255,255,255,.8); }

    {{-- Lists --}}
    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .footer-list li {
        font-size: 13px;
        color: rgba(255,255,255,.8);
        transition: color var(--trans);
    }
    .footer-list a {
        color: rgba(255,255,255,.8);
        text-decoration: none;
        font-weight: 500;
        transition: color var(--trans);
    }
    .footer-list li:hover,
    .footer-list a:hover { color: var(--gold); }

    {{-- Social Section --}}
    .footer-social {
        display: flex;
        gap: 12px;
        margin-bottom: 14px;
    }
    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(201,161,90,.15);
        border-radius: 50%;
        color: var(--gold);
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        transition: background var(--trans), color var(--trans);
    }
    .social-link:hover {
        background: var(--gold);
        color: var(--navy);
    }
    .footer-tagline {
        font-size: 12px;
        color: rgba(255,255,255,.65);
        margin: 0;
    }

    {{-- Footer Bottom --}}
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,.15);
        padding-top: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        text-align: center;
    }
    .footer-copyright {
        font-size: 13px;
        color: rgba(255,255,255,.7);
    }
    .footer-copyright strong { color: #fff; }
    .footer-bottom-links {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 12px;
    }
    .footer-bottom-links a {
        color: rgba(255,255,255,.7);
        text-decoration: none;
        transition: color var(--trans);
    }
    .footer-bottom-links a:hover { color: var(--gold); }
    .divider {
        color: rgba(255,255,255,.25);
    }

    @media(max-width: 768px) {
        .hotel-footer { padding: 60px 16px 30px; }
        .footer-grid { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 30px; }
        .footer-bottom { flex-direction: column; }
        .footer-bottom-links { gap: 8px; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs  = document.querySelectorAll('.cat-tab');
    const cards = document.querySelectorAll('.room-card');
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const cat = this.dataset.cat;
            cards.forEach(card => {
                card.style.display = (cat === 'all' || card.dataset.cat === cat) ? 'flex' : 'none';
            });
        });
    });

    // Room details modal
    const overlay   = document.getElementById('roomModalOverlay');
    const closeBtn  = document.getElementById('roomModalClose');
    const modalImg  = document.getElementById('roomModalImg');
    const modalCat  = document.getElementById('roomModalCategory');
    const modalName = document.getElementById('roomModalName');
    const modalDesc = document.getElementById('roomModalDesc');
    const modalPrice= document.getElementById('roomModalPrice');
    const modalCap  = document.getElementById('roomModalCapacity');
    const modalBook = document.getElementById('roomModalBook');

    function openModal(card) {
        modalImg.src        = card.dataset.roomImg;
        modalImg.alt        = card.dataset.roomName;
        modalCat.textContent  = card.dataset.roomCategory;
        modalName.textContent = card.dataset.roomName;
        modalDesc.textContent = card.dataset.roomDesc;
        modalPrice.textContent = card.dataset.roomPrice + ' / night';
        modalCap.textContent   = card.dataset.roomCapacity;
        modalBook.href = card.dataset.roomBook;
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('[data-room-details-btn]').forEach(btn => {
        btn.addEventListener('click', function () {
            openModal(this.closest('.room-card'));
        });
    });
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
});
</script>

@endsection

@section('content')
@endsection