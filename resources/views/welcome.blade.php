<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Trbltours') }} | Philippine Local Tours</title>

    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asimovian:wght@400;700&family=Cal+Sans&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sand-100: #fff8eb;
            --sand-200: #f5e8cc;
            --sand-300: #e7d2a9;
            --gold: #d4a563;
            --olive: #8f9d59;
            --brown-700: #6f5d52;
            --brown-800: #5a4a42;
            --brown-900: #3f2d22;
            --accent: #8b4513;
            --white: #ffffff;
            --shadow-lg: 0 14px 36px rgba(63, 45, 34, 0.18);
            --shadow-md: 0 10px 22px rgba(63, 45, 34, 0.12);
            --radius-xl: 22px;
            --radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            color: var(--brown-800);
            background: linear-gradient(180deg, #fffbf3 0%, #f7f0df 58%, #f2e7cf 100%);
            min-height: 100vh;
        }

        .brand-font {
            font-family: 'Asimovian', 'Instrument Sans', sans-serif;
            letter-spacing: 0.02em;
        }

        .hero-font {
            font-family: 'Cal Sans', 'Instrument Sans', sans-serif;
            letter-spacing: 0.02em;
            word-spacing: 0.08em;
        }

        .container {
            width: min(1200px, 92vw);
            margin: 0 auto;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(10px);
            background: rgba(255, 251, 244, 0.75);
            border-bottom: 1px solid rgba(139, 69, 19, 0.12);
        }

        .header-inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            width: clamp(38px, 4.6vw, 54px);
            height: clamp(38px, 4.6vw, 54px);
            border-radius: 12px;
            object-fit: cover;
            box-shadow: var(--shadow-md);
        }

        .brand-name {
            font-size: clamp(18px, 2.4vw, 24px);
            color: var(--accent);
        }

        .header-actions {
            display: inline-flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn {
            border: none;
            border-radius: 12px;
            min-height: 44px;
            padding: 10px 16px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            background: #71370f;
        }

        .btn-ghost {
            background: transparent;
            color: var(--brown-800);
            border: 1px solid rgba(139, 69, 19, 0.3);
        }

        .btn-ghost:hover {
            transform: translateY(-1px);
            background: rgba(139, 69, 19, 0.06);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: clamp(52px, 10vw, 96px) 0 clamp(28px, 6vw, 52px);
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 15% 18%, rgba(212, 165, 99, 0.22), transparent 40%),
                radial-gradient(circle at 85% 10%, rgba(143, 157, 89, 0.2), transparent 45%);
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            align-items: center;
            gap: clamp(18px, 3.5vw, 46px);
        }

        .hero-kicker {
            display: inline-flex;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(143, 157, 89, 0.12);
            border: 1px solid rgba(143, 157, 89, 0.3);
            font-weight: 700;
            color: #637039;
            margin-bottom: 16px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .hero h1 {
            color: var(--brown-900);
            font-size: clamp(34px, 6.2vw, 66px);
            line-height: 1.03;
            margin-bottom: 14px;
        }

        .hero-copy {
            max-width: 58ch;
            color: var(--brown-700);
            font-size: clamp(15px, 2vw, 18px);
            line-height: 1.65;
            margin-bottom: 26px;
        }

        .hero-cta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .hero-art {
            position: relative;
            border-radius: var(--radius-xl);
            min-height: 340px;
            background:
                linear-gradient(rgba(43, 30, 22, 0.2), rgba(43, 30, 22, 0.2)),
                url('{{ asset('hero/carousel4.jpg') }}') center/cover no-repeat;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(139, 69, 19, 0.2);
        }

        .hero-art-badge {
            position: absolute;
            right: 14px;
            bottom: 14px;
            padding: 10px 14px;
            border-radius: 12px;
            background: rgba(255, 248, 235, 0.92);
            font-weight: 700;
            color: var(--brown-900);
            box-shadow: var(--shadow-md);
            font-size: 13px;
        }

        .trust-bar {
            margin-top: clamp(12px, 3.5vw, 30px);
            margin-bottom: clamp(44px, 6vw, 72px);
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .trust-item {
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(139, 69, 19, 0.16);
            padding: 16px;
            box-shadow: var(--shadow-md);
            min-height: 98px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 6px;
        }

        .trust-label {
            font-size: 12px;
            color: var(--brown-700);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }

        .trust-value {
            font-size: clamp(15px, 2.3vw, 20px);
            color: var(--brown-900);
            font-weight: 800;
        }

        .why {
            padding-bottom: clamp(60px, 8vw, 100px);
        }

        .section-head {
            text-align: center;
            margin-bottom: clamp(20px, 4vw, 32px);
        }

        .section-head h2 {
            color: var(--brown-900);
            font-size: clamp(28px, 4.5vw, 42px);
            margin-bottom: 10px;
        }

        .section-head p {
            color: var(--brown-700);
            max-width: 64ch;
            margin: 0 auto;
            line-height: 1.65;
            font-size: clamp(14px, 2vw, 17px);
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .why-card {
            border-radius: var(--radius-lg);
            background: linear-gradient(180deg, #fffdf9 0%, #f8f1df 100%);
            border: 1px solid rgba(139, 69, 19, 0.14);
            box-shadow: var(--shadow-md);
            padding: 22px 18px;
        }

        .why-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(212, 165, 99, 0.2);
            color: var(--accent);
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .why-card h3 {
            color: var(--brown-900);
            font-size: clamp(19px, 2.4vw, 24px);
            margin-bottom: 8px;
        }

        .why-card p {
            color: var(--brown-700);
            line-height: 1.65;
            font-size: 14px;
        }

        @media (max-width: 980px) {
            .hero-inner {
                grid-template-columns: 1fr;
            }

            .hero-art {
                min-height: 300px;
            }

            .trust-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .why-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .header-inner {
                min-height: 70px;
                gap: 12px;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .btn {
                flex: 1 1 140px;
            }

            .hero {
                padding-top: 42px;
            }

            .trust-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="{{ url('/') }}">
                <img class="brand-logo" src="{{ asset('images/tribaltours_icon.png') }}" alt="Trbltours">
                <span class="brand-name brand-font">Trbltours</span>
            </a>

            <div class="header-actions">
                @auth
                    <a class="btn btn-ghost" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="btn btn-ghost" href="{{ route('login') }}">Log In</a>
                    @if (Route::has('register'))
                        <a class="btn btn-primary" href="{{ url('/signup.php') }}">Start Exploring</a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-inner">
                <div>
                    <span class="hero-kicker">Philippine Local Tour Platform</span>
                    <h1 class="hero-font">Discover The Philippines With Real Local Guides</h1>
                    <p class="hero-copy">
                        Trbltours connects travelers with trusted local experts for authentic, safe, and memorable adventures
                        across the islands, from hidden waterfalls to heritage towns and eco-trails.
                    </p>
                    <div class="hero-cta">
                        <a class="btn btn-primary" href="{{ url('/signup.php') }}">Book A Local Tour</a>
                        <a class="btn btn-ghost" href="#why-choose-us">Why Choose Us</a>
                    </div>
                </div>

                <div class="hero-art">
                    <div class="hero-art-badge">Curated tours in 80+ destinations</div>
                </div>
            </div>

            <div class="container trust-bar">
                <div class="trust-grid">
                    <article class="trust-item">
                        <span class="trust-label">Quality Assurance</span>
                        <p class="trust-value">DOT Accredited</p>
                    </article>
                    <article class="trust-item">
                        <span class="trust-label">Payment Options</span>
                        <p class="trust-value">GCash · Maya · Visa</p>
                    </article>
                    <article class="trust-item">
                        <span class="trust-label">Traveler Reviews</span>
                        <p class="trust-value">4.9/5 From 10k+ Travelers</p>
                    </article>
                    <article class="trust-item">
                        <span class="trust-label">Community</span>
                        <p class="trust-value">500+ Local Guides</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="why-choose-us" class="why">
            <div class="container">
                <div class="section-head">
                    <h2 class="hero-font">Why Choose Us</h2>
                    <p>
                        Built for curious travelers who want meaningful local experiences, with reliable support and
                        flexible options from planning to booking.
                    </p>
                </div>

                <div class="why-grid">
                    <article class="why-card">
                        <div class="why-icon">01</div>
                        <h3>Handpicked Local Guides</h3>
                        <p>
                            We vet and curate every guide for local expertise, hospitality, and safety standards so each tour
                            feels personal and trustworthy.
                        </p>
                    </article>

                    <article class="why-card">
                        <div class="why-icon">02</div>
                        <h3>Eco-Friendly Travel</h3>
                        <p>
                            Our platform promotes responsible tourism by supporting low-impact itineraries, local communities,
                            and conservation-minded tour partners.
                        </p>
                    </article>

                    <article class="why-card">
                        <div class="why-icon">03</div>
                        <h3>Flexible Booking</h3>
                        <p>
                            Choose schedules that fit your trip, adjust plans with ease, and confirm your adventure through
                            convenient and secure payment options.
                        </p>
                    </article>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
