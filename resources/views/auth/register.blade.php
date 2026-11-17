<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create Account | TapCard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --tap-dark: #151419;
            --tap-cyan: #08D5DB;
            --tap-lime: #B5FF58;
            --tap-muted: #858991;
            --tap-border: #E5E7EB;
            --tap-background: #F4F5F8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            color: var(--tap-dark);
            background: #fff;
        }

        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* LEFT PANEL */

        .auth-left {
            min-height: 100vh;
            padding: 28px 5%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #fff;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--tap-dark);
            width: fit-content;
        }

        /* TAPCARD FOUR-DOT BRAND SYMBOL */
        .brand-symbol {
            width: 36px;
            height: 36px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 4px;
            flex-shrink: 0;
            transform: rotate(-8deg);
            background: transparent;
            border-radius: 0;
        }

        .brand-symbol span {
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .brand-symbol span:nth-child(1) {
            background: #08D5DB;
        }

        .brand-symbol span:nth-child(2) {
            background: #B5FF58;
        }

        .brand-symbol span:nth-child(3) {
            background: #FF5B19;
        }

        .brand-symbol span:nth-child(4) {
            background: #FFEB00;
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.7px;
        }

        .brand-name span {
            color: #08BFC5;
        }

        .auth-content {
            width: 100%;
            max-width: 440px;
            margin: auto;
            padding: 40px 0;
        }

        .auth-heading {
            margin-bottom: 27px;
        }

        .auth-heading h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(30px, 3vw, 38px);
            font-weight: 600;
            letter-spacing: -1.5px;
            line-height: 1.2;
            margin: 0 0 12px;
            color: var(--tap-dark);
        }

        .auth-heading p {
            font-size: 14px;
            line-height: 1.6;
            color: var(--tap-muted);
            margin: 0;
        }

        .auth-form-group {
            margin-bottom: 17px;
        }

        .auth-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #34363D;
        }

        .auth-input {
            width: 100%;
            height: 47px;
            padding: 0 14px;
            border: 1px solid #DCDDE2;
            border-radius: 8px;
            outline: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--tap-dark);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        .auth-input::placeholder {
            color: #A5A7AE;
        }

        .auth-input:focus {
            border-color: var(--tap-cyan);
            box-shadow: 0 0 0 3px rgba(8, 213, 219, .12);
        }

        .auth-error {
            display: block;
            margin-top: 7px;
            color: #DC2626;
            font-size: 12px;
        }

        .auth-submit {
            width: 100%;
            height: 49px;
            border: none;
            border-radius: 8px;
            background: var(--tap-dark);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .2s, box-shadow .2s;
        }

        .auth-submit:hover {
            background: #25232D;
            box-shadow: 0 7px 18px rgba(21, 20, 25, .15);
            transform: translateY(-1px);
        }

        .auth-submit span {
            color: var(--tap-cyan);
        }

        .auth-login {
            text-align: center;
            margin-top: 25px;
            color: #858991;
            font-size: 12px;
        }

        .auth-login a {
            color: #079FA5;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .auth-login a:hover {
            color: var(--tap-dark);
            text-decoration: underline;
        }

        .auth-footer {
            color: #9A9CA3;
            font-size: 11px;
        }

        /* RIGHT PANEL */

        .auth-right {
            min-height: 100vh;
            background: var(--tap-background);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .orb-scene {
            width: 100%;
            height: 100%;
            min-height: 600px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .orb {
            width: 180px;
            height: 180px;
            position: relative;
            border-radius: 50%;
            background: linear-gradient(145deg,
                    #48F0EC 0%,
                    #08D5DB 45%,
                    #079FA5 100%);
            box-shadow:
                inset -18px -20px 35px rgba(0, 80, 85, .2),
                inset 12px 12px 30px rgba(255, 255, 255, .35),
                0 0 70px rgba(8, 213, 219, .24);
            z-index: 2;
            animation: orbFloat 6s ease-in-out infinite;
        }

        .orb::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, .45),
                    transparent 45%);
        }

        .orb-reflection {
            position: absolute;
            width: 280px;
            height: 115px;
            top: calc(50% + 90px);
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(to bottom,
                    rgba(8, 213, 219, .42),
                    rgba(8, 213, 219, .10) 55%,
                    transparent);
            filter: blur(23px);
            opacity: .8;
            z-index: 1;
        }

        .orb-platform {
            position: absolute;
            width: 320px;
            height: 2px;
            top: calc(50% + 90px);
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(90deg,
                    transparent,
                    rgba(8, 213, 219, .5),
                    rgba(8, 213, 219, .8),
                    rgba(8, 213, 219, .5),
                    transparent);
            z-index: 3;
        }

        .orb-glow {
            position: absolute;
            width: 460px;
            height: 460px;
            border-radius: 50%;
            background: radial-gradient(circle,
                    rgba(8, 213, 219, .13) 0%,
                    rgba(8, 213, 219, .05) 35%,
                    transparent 70%);
            z-index: 0;
        }

        .auth-right-decoration {
            position: absolute;
            width: 420px;
            height: 420px;
            border: 1px solid rgba(21, 20, 25, .035);
            border-radius: 50%;
        }

        .auth-right-decoration::before {
            content: "";
            position: absolute;
            inset: 35px;
            border: 1px solid rgba(21, 20, 25, .035);
            border-radius: 50%;
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .auth-page {
                grid-template-columns: 1fr;
            }

            .auth-left {
                min-height: 100vh;
                padding: 24px 7%;
            }

            .auth-content {
                max-width: 460px;
            }

            .auth-right {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .auth-left {
                padding: 22px 24px;
            }

            .auth-content {
                padding: 35px 0;
            }

            .auth-heading h1 {
                font-size: 32px;
            }

            .auth-footer {
                padding-bottom: 5px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .orb {
                animation: none;
            }

            * {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <div class="auth-page">

        <!-- LEFT: REGISTRATION FORM -->
        <main class="auth-left">

            <a href="{{ url('/') }}" class="auth-brand">
                <span class="brand-symbol" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </span>

                <span class="brand-name">
                    TAPCARD
                </span>
            </a>

            <div class="auth-content">

                <div class="auth-heading">
                    <h1>Create your account</h1>
                    <p>Get started with TapCard. Create your account to build and share your digital identity.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NAME -->
                    <div class="auth-form-group">
                        <label for="name" class="auth-label">
                            Full name
                        </label>

                        <input
                            id="name"
                            class="auth-input"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            required
                            autofocus
                            autocomplete="name">

                        @error('name')
                        <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="auth-form-group">
                        <label for="email" class="auth-label">
                            Email address
                        </label>

                        <input
                            id="email"
                            class="auth-input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            required
                            autocomplete="username">

                        @error('email')
                        <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="auth-form-group">
                        <label for="password" class="auth-label">
                            Password
                        </label>

                        <input
                            id="password"
                            class="auth-input"
                            type="password"
                            name="password"
                            placeholder="Create a password"
                            required
                            autocomplete="new-password">

                        @error('password')
                        <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="auth-form-group">
                        <label for="password_confirmation" class="auth-label">
                            Confirm password
                        </label>

                        <input
                            id="password_confirmation"
                            class="auth-input"
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm your password"
                            required
                            autocomplete="new-password">

                        @error('password_confirmation')
                        <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- SUBMIT -->
                    <button type="submit" class="auth-submit">
                        Create account <span>→</span>
                    </button>

                </form>

                <!-- LOGIN LINK -->
                <div class="auth-login">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign in</a>
                </div>

            </div>

            <footer class="auth-footer">
                © TapCard {{ date('Y') }}. All rights reserved.
            </footer>

        </main>


        <!-- RIGHT: VISUAL PANEL -->
        <aside class="auth-right">

            <div class="auth-right-decoration"></div>

            <div class="orb-scene">

                <div class="orb-glow"></div>

                <div class="orb"></div>

                <div class="orb-platform"></div>

                <div class="orb-reflection"></div>

            </div>

        </aside>

    </div>

</body>

</html>