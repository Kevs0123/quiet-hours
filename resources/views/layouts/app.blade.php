<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quiet Hours') &mdash; Quiet Hours Hotel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:    #1b2a4a;
            --navy2:   #243560;
            --gold:    #c9a15a;
            --gold2:   #a6833e;
            --cream:   #f7f4ee;
            --cream2:  #ede9df;
            --ink:     #22242b;
            --muted:   #6b6860;
            --danger:  #b3261e;
            --success: #1a6b3a;
            --white:   #ffffff;
            --shadow:  0 4px 24px rgba(27,42,74,.10);
            --radius:  12px;
            --trans:   0.25s ease;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .site-header {
            background: var(--navy);
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 16px rgba(0,0,0,.25);
        }
        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: var(--white);
            text-decoration: none;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-icon {
            width: 34px; height: 34px;
            background: var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .brand span { color: var(--gold); }
        .site-nav { display: flex; align-items: center; gap: 6px; }
        .site-nav a {
            color: rgba(255,255,255,.80);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: background var(--trans), color var(--trans);
        }
        .site-nav a:hover, .site-nav a.active {
            background: rgba(201,161,90,.15);
            color: var(--gold);
        }
        .site-nav .nav-book {
            background: var(--gold);
            color: var(--navy);
            font-weight: 600;
            margin-left: 6px;
        }
        .site-nav .nav-book:hover { background: var(--gold2); color: var(--white); }
        .site-main {
            flex: 1;
            max-width: 1600px;
            width: 100%;
            margin: 40px auto;
            padding: 0 24px 60px;
        }
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 22px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: fadeDown .35s ease;
        }
        .alert-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .alert-success { background: #e8f5ee; color: var(--success); border: 1px solid #b8e0c8; }
        .alert-error   { background: #fde9e7; color: var(--danger);  border: 1px solid #f3c0bb; }
        .alert ul { margin: 6px 0 0 16px; }
        .card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 36px 40px;
            box-shadow: var(--shadow);
            margin-bottom: 28px;
            animation: fadeUp .4s ease;
        }
        .card-sm { padding: 24px 28px; }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-header h1 { margin-bottom: 0; }
        h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--navy); margin-bottom: 10px; }
        h2 { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--navy); margin-bottom: 10px; }
        h3 { font-size: 16px; color: var(--navy); margin-bottom: 10px; font-weight: 600; }
        p  { color: var(--muted); line-height: 1.7; margin-bottom: 12px; }
        p:last-child { margin-bottom: 0; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform var(--trans), box-shadow var(--trans), background var(--trans), color var(--trans);
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,0,0,.15); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--navy); color: var(--white); }
        .btn-primary:hover { background: var(--navy2); }
        .btn-gold { background: var(--gold); color: var(--navy); }
        .btn-gold:hover { background: var(--gold2); color: var(--white); }
        .btn-danger { background: var(--danger); color: var(--white); }
        .btn-danger:hover { background: #8b1a15; }
        .btn-outline { background: transparent; border: 1.5px solid var(--navy); color: var(--navy); }
        .btn-outline:hover { background: var(--navy); color: var(--white); }
        .btn-sm { padding: 7px 14px; font-size: 13px; }
        .field { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        input[type=text], input[type=number], input[type=email],
        input[type=password], input[type=date], input[type=file],
        select, textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #d6d0c2;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: #fff;
            transition: border-color var(--trans), box-shadow var(--trans);
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,161,90,.18);
        }
        input[readonly] { background: var(--cream); color: var(--muted); cursor: default; }
        .error-text { color: var(--danger); font-size: 12.5px; margin-top: 5px; display: block; }
        input.is-invalid, select.is-invalid, textarea.is-invalid { border-color: var(--danger); }
        .file-drop-zone {
            border: 2px dashed #d6d0c2;
            border-radius: 10px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color var(--trans), background var(--trans);
            position: relative;
        }
        .file-drop-zone:hover, .file-drop-zone.dragover { border-color: var(--gold); background: #fdf9f1; }
        .file-drop-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .file-drop-icon { font-size: 34px; margin-bottom: 8px; }
        .file-drop-text { font-size: 14px; color: var(--muted); }
        .file-drop-text strong { color: var(--navy); }
        #file-name-display { margin-top: 10px; font-size: 13px; color: var(--success); font-weight: 500; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { border-bottom: 2px solid var(--cream2); }
        th { text-align: left; padding: 10px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); }
        td { padding: 13px 14px; font-size: 14px; border-bottom: 1px solid var(--cream2); vertical-align: middle; }
        tbody tr { transition: background var(--trans); }
        tbody tr:hover { background: #faf8f4; }
        tbody tr:last-child td { border-bottom: none; }
        td a { color: var(--navy); font-weight: 600; text-decoration: none; }
        td a:hover { color: var(--gold2); text-decoration: underline; }
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge::before { content: ''; width: 7px; height: 7px; border-radius: 50%; }
        .badge-available { background: #e6f5ee; color: var(--success); }
        .badge-available::before { background: var(--success); }
        .badge-unavailable { background: #fde9e7; color: var(--danger); }
        .badge-unavailable::before { background: var(--danger); }
        .badge-pending { background: #fdf6ec; color: var(--gold2); }
        .badge-pending::before { background: var(--gold2); }
        .actions { display: flex; gap: 6px; align-items: center; }
        .wizard-steps { display: flex; gap: 0; margin-bottom: 36px; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(27,42,74,.08); }
        .wizard-step { flex: 1; padding: 13px 10px; text-align: center; font-size: 13px; font-weight: 600; background: var(--cream2); color: var(--muted); transition: background var(--trans), color var(--trans); }
        .wizard-step.done   { background: var(--gold); color: var(--navy); }
        .wizard-step.active { background: var(--navy); color: var(--white); }
        .wizard-step .step-num   { display: block; font-size: 18px; font-family: 'Playfair Display', serif; margin-bottom: 2px; }
        .wizard-step .step-label { font-size: 11px; text-transform: uppercase; letter-spacing: .4px; }
        .detail-list { width: 100%; border-collapse: collapse; }
        .detail-list th { width: 180px; text-align: left; padding: 13px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); background: var(--cream); border-bottom: 1px solid var(--cream2); }
        .detail-list td { padding: 13px 16px; font-size: 15px; border-bottom: 1px solid var(--cream2); }
        .detail-list tr:last-child th, .detail-list tr:last-child td { border-bottom: none; }
        .preview-img { max-width: 380px; width: 100%; border-radius: 10px; border: 1px solid var(--cream2); box-shadow: 0 4px 20px rgba(0,0,0,.08); margin-top: 14px; }
        .pdf-download { display: inline-flex; align-items: center; gap: 8px; background: var(--cream); border: 1.5px solid var(--cream2); border-radius: 8px; padding: 12px 18px; margin-top: 14px; text-decoration: none; color: var(--navy); font-weight: 600; font-size: 14px; transition: background var(--trans), border-color var(--trans); }
        .pdf-download:hover { background: var(--cream2); border-color: var(--gold); }
        nav[aria-label="pagination"] { margin-top: 20px; }
        .empty-state { text-align: center; padding: 48px 20px; color: var(--muted); }
        .empty-state-icon { font-size: 48px; margin-bottom: 12px; }
        hr { border: none; border-top: 1px solid var(--cream2); margin: 24px 0; }
        .site-footer { background: var(--navy); color: rgba(255,255,255,.5); text-align: center; padding: 22px; font-size: 13px; }
        .site-footer a { color: var(--gold); text-decoration: none; }
        @keyframes fadeUp   { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        @media (max-width: 640px) {
            .site-header { padding: 0 18px; }
            .card { padding: 24px 20px; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <a href="{{ route('home') }}" class="brand">
            <div class="brand-icon">🏨</div>
            QUIET <span>&nbsp;HOURS</span>
        </a>
        <nav class="site-nav">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>📊 Dashboard</a>
                    <a href="{{ route('admin.clients.index') }}" @class(['active' => request()->routeIs('admin.clients.*')])>👥 Clients</a>
                    <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>🗂 Categories</a>
                    <a href="{{ route('admin.rooms.index') }}" @class(['active' => request()->routeIs('admin.rooms.*')])>🛏 Rooms</a>
                    <a href="{{ route('admin.bookings') }}" @class(['active' => request()->routeIs('admin.bookings*')])>📋 Bookings</a>
                @else
                    <a href="{{ route('booking.dashboard') }}" @class(['active' => request()->routeIs('booking.dashboard')])>📊 Dashboard</a>
                    <a href="{{ route('booking.home') }}" @class(['active' => request()->routeIs('booking.home')])>🛏 My Booking</a>
                    <a href="{{ route('booking.history') }}" @class(['active' => request()->routeIs('booking.history')])>📜 History</a>
                @endif
                <span style="color:rgba(255,255,255,.3);margin:0 2px;">|</span>
                <span style="color:rgba(255,255,255,.6);font-size:13px;padding:8px 6px;">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="border-color:rgba(255,255,255,.35);color:rgba(255,255,255,.75);">Sign Out</button>
                </form>
            @else
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
                <a href="{{ route('login') }}" @class(['active' => request()->routeIs('login')])>Sign In</a>
                <a href="{{ route('register') }}" class="nav-book">✦ Book Now</a>
            @endauth
        </nav>
    </header>

    <main class="site-main">
        @hasSection('fullpage')
            @yield('fullpage')
        @else
            @if(session('success'))
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <div>{{ session('error') }}</div>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <div>
                        <strong>Please fix the following:</strong>
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            @yield('content')
        @endif
    </main>

    @unless(request()->routeIs('home') || request()->routeIs('admin.*') || request()->routeIs('booking.*'))
    <footer class="site-footer">
        &copy; {{ date('Y') }} <a href="{{ route('home') }}">Quiet Hours Hotel</a> &mdash; A calm stay, every time.
    </footer>
    @endunless

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const zone = document.querySelector('.file-drop-zone');
            if (!zone) return;
            const input   = zone.querySelector('input[type=file]');
            const display = document.getElementById('file-name-display');
            zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
            zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
            zone.addEventListener('drop', e => {
                e.preventDefault(); zone.classList.remove('dragover');
                if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; showName(e.dataTransfer.files[0].name); }
            });
            if (input) input.addEventListener('change', () => { if (input.files.length) showName(input.files[0].name); });
            function showName(name) { if (display) display.textContent = '📎 ' + name; }
        });
    </script>
</body>
</html>
