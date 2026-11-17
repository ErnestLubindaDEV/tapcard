
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#151419">

    <title>TapCard — Your Identity, One Tap Away</title>

    <meta name="description" content="Create your digital business card, share it instantly and connect with people wherever you go.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --dark: #151419;
            --dark-soft: #1d1c22;
            --white: #f7f7f5;
            --muted: #96949e;

            --cyan: #08d5db;
            --lime: #b5ff58;
            --orange: #ff5b19;
            --yellow: #ffeb00;
            --blue: #3158ff;

            --font-display: 'Outfit', sans-serif;
            --font-body: 'DM Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--dark);
            color: var(--white);
            font-family: var(--font-body);
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
        }

        .site-wrapper {
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
        }

        /* NAVIGATION */

        .navbar {
            position: relative;
            z-index: 20;
            max-width: 1500px;
            margin: auto;
            padding: 28px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--font-display);
            font-size: 29px;
            font-weight: 700;
            letter-spacing: -1.5px;
        }

        .brand-symbol {
            width: 36px;
            height: 36px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3px;
            transform: rotate(-8deg);
        }

        .brand-symbol span {
            border-radius: 50%;
            background: var(--cyan);
        }

        .brand-symbol span:nth-child(2) {
            background: var(--lime);
        }

        .brand-symbol span:nth-child(3) {
            background: var(--orange);
        }

        .brand-symbol span:nth-child(4) {
            background: var(--yellow);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 36px;
        }

        .nav-links a {
            color: #aaa8b1;
            font-size: 13px;
            transition: color .3s ease;
        }

        .nav-links a:hover {
            color: var(--cyan);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .login-link {
            color: var(--white);
            font-size: 13px;
        }

        .nav-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--cyan);
            color: var(--dark);
            padding: 13px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            transition: .3s ease;
        }

        .nav-cta:hover {
            background: var(--lime);
            transform: translateY(-3px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: 0;
            color: white;
            font-size: 25px;
            cursor: pointer;
        }

        /* HERO */

        .hero {
            max-width: 1500px;
            min-height: 750px;
            margin: auto;
            padding: 40px 7% 90px;
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            gap: 50px;
            align-items: center;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 30px 0;
            animation: fadeUp .9s ease both;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--cyan);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .eyebrow::before {
            content: '';
            width: 27px;
            height: 2px;
            background: var(--cyan);
        }

        .hero h1 {
            font-family: var(--font-display);
            font-size: clamp(65px, 7.5vw, 112px);
            line-height: .92;
            letter-spacing: -7px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .hero h1 span {
            display: block;
        }

        .hero h1 .accent {
            color: var(--cyan);
        }

        /* DECORATIVE DOTS */

        .brand-dots {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 34px;
        }

        .dot-shape {
            width: 25px;
            height: 25px;
            border-radius: 50%;
        }

        .dot-cyan {
            background: var(--cyan);
        }

        .dot-lime {
            background: var(--lime);
        }

        .dot-orange {
            background: var(--orange);
        }

        .dot-yellow {
            background: var(--yellow);
        }

        .hero-description {
            max-width: 405px;
            margin-top: 90px;
            color: var(--muted);
            font-size: 17px;
            line-height: 1.8;
        }

        .hero-description strong {
            color: var(--white);
            font-weight: 500;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-top: 30px;
        }

        .primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 24px;
            border-radius: 50px;
            background: var(--cyan);
            color: var(--dark);
            font-size: 13px;
            font-weight: 700;
            transition: .3s ease;
        }

        .primary-btn:hover {
            background: var(--lime);
            transform: translateY(-4px);
        }

        .secondary-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--white);
            font-size: 13px;
        }

        .secondary-btn i {
            font-size: 19px;
        }

        .secondary-btn:hover {
            color: var(--cyan);
        }

        .hero-trust {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 65px;
            color: #d7d5dc;
            font-family: var(--font-display);
            font-size: 19px;
            font-weight: 600;
        }

        .hero-trust i {
            font-size: 31px;
            color: var(--cyan);
        }

        .hero-trust small {
            display: block;
            font-family: var(--font-body);
            font-size: 10px;
            color: var(--muted);
            font-weight: 400;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        /* CARD SHOWCASE */

        .showcase {
            position: relative;
            height: 700px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            align-items: center;
            animation: fadeUp 1s ease .2s both;
        }

        .card-column {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .card-column:first-child {
            transform: translateY(-30px);
        }

        .card-column:last-child {
            transform: translateY(35px);
        }

        .demo-card {
            position: relative;
            min-height: 265px;
            padding: 26px;
            border-radius: 25px;
            overflow: hidden;
            transition: transform .4s ease;
        }

        .demo-card:hover {
            transform: translateY(-8px) rotate(-1deg);
        }

        .card-white {
            background: #f2f2f2;
            color: #19191d;
        }

        .card-orange {
            background: var(--orange);
            color: var(--yellow);
        }

        .card-yellow {
            background: var(--yellow);
            color: #db5a24;
        }

        .card-blue {
            background: var(--blue);
            color: var(--cyan);
        }

        .card-lime {
            background: var(--lime);
            color: #1b1b1b;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .card-name {
            font-family: var(--font-display);
            font-size: 27px;
            line-height: 1.05;
            font-weight: 400;
        }

        .card-icon {
            font-size: 23px;
            color: #151419;
        }

        .card-photo {
            width: 165px;
            height: 155px;
            object-fit: cover;
            display: block;
            margin: 22px auto 0;
            border-radius: 45% 45% 40% 40%;
        }

        .card-orange .card-photo,
        .card-blue .card-photo {
            border-radius: 50%;
            border: 9px solid rgba(255,255,255,.45);
        }

        .card-yellow .card-photo {
            border-radius: 48% 48% 25% 25%;
        }

        .card-white .card-photo {
            filter: grayscale(100%);
            width: 145px;
            height: 145px;
            border-radius: 30px;
        }

        .card-footer {
            position: absolute;
            bottom: 19px;
            left: 25px;
            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 700;
            opacity: .7;
        }

        /* CARD FLOATING LABEL */

        .floating-label {
            position: absolute;
            z-index: 5;
            background: var(--dark-soft);
            border: 1px solid #37353e;
            padding: 13px 17px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 15px 40px rgba(0,0,0,.25);
            animation: float 5s ease-in-out infinite;
        }

        .floating-label i {
            font-size: 21px;
            color: var(--cyan);
        }

        .floating-label strong {
            display: block;
            font-size: 11px;
        }

        .floating-label small {
            display: block;
            color: var(--muted);
            font-size: 9px;
            margin-top: 2px;
        }

        .label-one {
            left: -45px;
            top: 43%;
        }

        .label-two {
            right: -20px;
            bottom: 9%;
            animation-delay: 1.5s;
        }

        /* FEATURE STRIP */

        .feature-strip {
            border-top: 1px solid #302e36;
            border-bottom: 1px solid #302e36;
            padding: 26px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            flex-wrap: wrap;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #c5c3cb;
            font-size: 12px;
        }

        .feature-item i {
            color: var(--cyan);
            font-size: 20px;
        }

        /* BOTTOM INTRO */

        .intro-section {
            max-width: 1500px;
            margin: auto;
            padding: 110px 7%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: center;
        }

        .intro-heading {
            font-family: var(--font-display);
            font-size: clamp(42px, 5vw, 68px);
            line-height: 1;
            letter-spacing: -3px;
            font-weight: 500;
        }

        .intro-heading span {
            color: var(--lime);
        }

        .intro-copy {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.9;
        }

        .intro-copy a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 25px;
            color: var(--cyan);
            font-size: 13px;
            font-weight: 700;
        }

        /* FOOTER */

        .footer {
            padding: 35px 7%;
            border-top: 1px solid #302e36;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            color: var(--muted);
            font-size: 11px;
        }

        .footer-links {
            display: flex;
            gap: 22px;
        }

        .footer-links a:hover {
            color: var(--cyan);
        }

        /* ANIMATIONS */

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-12px);
            }
        }

        /* RESPONSIVE */

        @media (max-width: 1100px) {
            .navbar,
            .hero,
            .intro-section {
                padding-left: 5%;
                padding-right: 5%;
            }

            .hero {
                gap: 25px;
            }

            .hero h1 {
                font-size: clamp(60px, 8vw, 90px);
                letter-spacing: -5px;
            }

            .showcase {
                gap: 14px;
            }

            .demo-card {
                padding: 20px;
                min-height: 235px;
            }

            .card-name {
                font-size: 23px;
            }

            .card-photo {
                width: 135px;
                height: 130px;
            }
        }

        @media (max-width: 800px) {
            .navbar {
                padding: 23px 6%;
            }

            .mobile-menu-btn {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 80px;
                left: 5%;
                right: 5%;
                padding: 25px;
                border-radius: 18px;
                background: #24222a;
                flex-direction: column;
                align-items: flex-start;
                gap: 22px;
                border: 1px solid #393640;
            }

            .nav-links.active {
                display: flex;
            }

            .nav-actions {
                gap: 12px;
            }

            .login-link {
                display: none;
            }

            .hero {
                grid-template-columns: 1fr;
                padding: 65px 6% 70px;
                gap: 50px;
            }

            .hero-content {
                padding: 0;
            }

            .hero h1 {
                font-size: clamp(66px, 12vw, 100px);
                letter-spacing: -5px;
            }

            .hero-description {
                margin-top: 45px;
            }

            .hero-trust {
                margin-top: 45px;
            }

            .showcase {
                height: 600px;
                max-width: 550px;
                width: 100%;
                margin: auto;
            }

            .card-column:first-child {
                transform: translateY(-20px);
            }

            .card-column:last-child {
                transform: translateY(25px);
            }

            .demo-card {
                min-height: 245px;
            }

            .intro-section {
                grid-template-columns: 1fr;
                padding-top: 80px;
                padding-bottom: 80px;
                gap: 30px;
            }

            .feature-strip {
                justify-content: flex-start;
            }
        }

        @media (max-width: 480px) {
            .brand {
                font-size: 25px;
            }

            .nav-cta {
                padding: 11px 15px;
                font-size: 11px;
            }

            .hero {
                padding-top: 45px;
            }

            .hero h1 {
                font-size: clamp(54px, 13vw, 72px);
                letter-spacing: -4px;
            }

            .eyebrow {
                font-size: 9px;
            }

            .hero-description {
                font-size: 14px;
                margin-top: 38px;
            }

            .hero-actions {
                flex-wrap: wrap;
                gap: 18px;
            }

            .primary-btn {
                padding: 14px 18px;
                font-size: 12px;
            }

            .showcase {
                height: 470px;
                gap: 10px;
            }

            .card-column {
                gap: 12px;
            }

            .demo-card {
                min-height: 190px;
                padding: 15px;
                border-radius: 18px;
            }

            .card-name {
                font-size: 19px;
            }

            .card-photo {
                width: 110px;
                height: 105px;
                margin-top: 15px;
            }

            .card-white .card-photo {
                width: 100px;
                height: 100px;
            }

            .card-footer {
                bottom: 13px;
                left: 15px;
                font-size: 7px;
            }

            .floating-label {
                padding: 10px;
            }

            .label-one {
                left: -5px;
            }

            .label-two {
                right: -5px;
            }

            .feature-strip {
                gap: 18px;
                padding: 25px 6%;
            }

            .feature-item {
                font-size: 10px;
            }

            .intro-heading {
                font-size: 46px;
            }

            .footer {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<div class="site-wrapper">

    <!-- NAVIGATION -->

    <header class="navbar">

        <a href="{{ url('/') }}" class="brand">
            <span class="brand-symbol">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </span>
            TAPCARD
        </a>

        <nav class="nav-links" id="navLinks">
            <a href="{{ url('/') }}">Home</a>
            <a href="#features">Features</a>
            <a href="#how-it-works">How it works</a>
            <a href="#about">About</a>
        </nav>

        <div class="nav-actions">

            @auth
                <a href="{{ url('/dashboard') }}" class="login-link">
                    Dashboard
                </a>
            @else
                @if(Route::has('login'))
                    <a href="{{ route('login') }}" class="login-link">
                        Log in
                    </a>
                @endif
            @endauth

            @auth
                <a href="{{ url('/dashboard') }}" class="nav-cta">
                    My Cards <i class="ri-arrow-right-up-line"></i>
                </a>
            @else
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-cta">
                        Get Started <i class="ri-arrow-right-up-line"></i>
                    </a>
                @endif
            @endauth

            <button class="mobile-menu-btn" id="mobileMenuBtn"
                    aria-label="Toggle navigation"
                    aria-expanded="false">
                <i class="ri-menu-3-line"></i>
            </button>

        </div>

    </header>


    <!-- HERO SECTION -->

    <main>

        <section class="hero">

            <div class="hero-content">

                <div class="eyebrow">
By Rideve               </div>

                <h1>
                    <span>YOUR</span>
                    <span>IDENTITY.</span>
                    <span class="accent">ONE TAP.</span>
                </h1>

                <div class="brand-dots">
                    <span class="dot-shape dot-cyan"></span>
                    <span class="dot-shape dot-cyan"></span>
                    <span class="dot-shape dot-lime"></span>
                    <span class="dot-shape dot-orange"></span>
                    <span class="dot-shape dot-yellow"></span>
                </div>

                <p class="hero-description">
                    Move beyond traditional business cards.
                    With <strong>TapCard</strong>, create your digital identity,
                    share your details instantly and turn every introduction
                    into a meaningful connection.
                </p>

                <div class="hero-actions">

                    @guest
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="primary-btn">
                                Create Your Card
                                <i class="ri-arrow-right-up-line"></i>
                            </a>
                        @endif
                    @else
                        <a href="{{ url('/dashboard') }}" class="primary-btn">
                            Go to Dashboard
                            <i class="ri-arrow-right-up-line"></i>
                        </a>
                    @endguest

                    <a href="#how-it-works" class="secondary-btn">
                        <i class="ri-play-circle-line"></i>
                        Discover how it works
                    </a>

                </div>

                <div class="hero-trust">
                    <i class="ri-fingerprint-line"></i>
                    <div>
                        TapCard
                        <small>CREATE. CONNECT. SHARE.</small>
                    </div>
                </div>

            </div>


            <!-- BUSINESS CARD SHOWCASE -->

            <div class="showcase">

                <!-- LEFT COLUMN -->

                <div class="card-column">

                    <div class="demo-card card-white">

                        <div class="card-top">
                            <div class="card-name">
                            YOUR DIGITAL IDENTITY
                            </div>
                            <i class="ri-fingerprint-line card-icon"></i>
                        </div>

                        <img
                            src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=85"
                            alt="Professional profile"
                            class="card-photo"
                        >

                        <div class="card-footer">
                        </div>

                    </div>


                    <div class="demo-card card-yellow">

                        <div class="card-top">
                            <div class="card-name">
                            EXPRESS YOURSELF
                            </div>
                            <i class="ri-fingerprint-line card-icon"></i>
                        </div>

                        <img
                            src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=500&auto=format&fit=crop&q=85"
                            alt="Creative professional"
                            class="card-photo"
                        >

               
                    </div>

                </div>


                <!-- RIGHT COLUMN -->

                <div class="card-column">

                    <div class="demo-card card-lime">

                        <div class="card-top">
                            <div class="card-name">
                            MAKE AN IMPRESSION
                            </div>
                            <i class="ri-fingerprint-line card-icon"></i>
                        </div>

                        <img
                            src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=500&auto=format&fit=crop&q=85"
                            alt="Business professional"
                            class="card-photo"
                        >

 

                    </div>


                    <div class="demo-card card-orange">

                        <div class="card-top">
                            <div class="card-name">
                                STAND OUT
                            </div>
                            <i class="ri-fingerprint-line card-icon"></i>
                        </div>

                        <img
                            src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=500&auto=format&fit=crop&q=85"
                            alt="Digital business card profile"
                            class="card-photo"
                        >

                    </div>


                    <div class="demo-card card-blue">

                        <div class="card-top">
                            <div class="card-name">
 CONNECT DIFFERENTLY                            </div>
                            <i class="ri-fingerprint-line card-icon"></i>
                        </div>

                        <img
                            src="https://images.unsplash.com/photo-1504593811423-6dd665756598?w=500&auto=format&fit=crop&q=85"
                            alt="Professional profile portrait"
                            class="card-photo"
                        >


                    </div>

                </div>


                <!-- FLOATING LABELS -->

                <div class="floating-label label-one">
                    <i class="ri-qr-code-line"></i>
                    <div>
                        <strong>Instant sharing</strong>
                        <small>One scan. One connection.</small>
                    </div>
                </div>

                <div class="floating-label label-two">
                    <i class="ri-contacts-book-2-line"></i>
                    <div>
                        <strong>Save my contact</strong>
                        <small>Always within reach.</small>
                    </div>
                </div>

            </div>

        </section>


        <!-- FEATURE STRIP -->

        <section class="feature-strip" id="features">

            <div class="feature-item">
                <i class="ri-qr-code-line"></i>
                QR Code Sharing
            </div>

            <div class="feature-item">
                <i class="ri-smartphone-line"></i>
                Mobile Ready
            </div>

            <div class="feature-item">
                <i class="ri-contacts-book-line"></i>
                Save Contacts
            </div>

            <div class="feature-item">
                <i class="ri-links-line"></i>
                Social Connections
            </div>

            <div class="feature-item">
                <i class="ri-bar-chart-box-line"></i>
                Smart Analytics
            </div>

        </section>


        <!-- INTRO SECTION -->

        <section class="intro-section" id="how-it-works">

            <div>

                <div class="eyebrow">
                    NETWORKING, REIMAGINED
                </div>

                <h2 class="intro-heading">
                    LESS PAPER.<br>
                    MORE <span>CONNECTIONS.</span>
                </h2>

            </div>

            <div class="intro-copy" id="about">

                Your business card should do more than display
                your phone number.

                It should tell your story, represent your brand
                and make connecting effortless.

                With TapCard, your professional identity lives
                in one simple, shareable digital space.

                <br>

                @guest
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}">
                            Create your digital identity
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    @endif
                @else
                    <a href="{{ url('/dashboard') }}">
                        Manage your digital identity
                        <i class="ri-arrow-right-line"></i>
                    </a>
                @endguest

            </div>

        </section>

    </main>


    <!-- FOOTER -->

    <footer class="footer">

        <a href="{{ url('/') }}" class="brand">
            <span class="brand-symbol">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </span>
            TAPCARD
        </a>

        <div>
            © {{ date('Y') }} TapCard. All rights reserved.
        </div>

        <div class="footer-links">
            <a href="#features">Features</a>
            <a href="#about">About</a>

            @if(Route::has('login'))
                <a href="{{ route('login') }}">Login</a>
            @endif
        </div>

    </footer>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const menuButton = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        if (menuButton && navLinks) {

            menuButton.addEventListener('click', function () {

                const isOpen = navLinks.classList.toggle('active');

                menuButton.setAttribute('aria-expanded', isOpen);

                menuButton.innerHTML = isOpen
                    ? '<i class="ri-close-line"></i>'
                    : '<i class="ri-menu-3-line"></i>';

            });

            navLinks.querySelectorAll('a').forEach(link => {

                link.addEventListener('click', function () {

                    navLinks.classList.remove('active');

                    menuButton.setAttribute('aria-expanded', 'false');

                    menuButton.innerHTML =
                        '<i class="ri-menu-3-line"></i>';

                });

            });

        }

    });
</script>

</body>
</html>
