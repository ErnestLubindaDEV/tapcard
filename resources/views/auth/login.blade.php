
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sign In | TapCard</title>

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
            padding: 55px 0;
        }

        .auth-heading {
            margin-bottom: 34px;
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
            margin-bottom: 21px;
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
            height: 49px;
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

        .auth-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 2px 0 23px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 12px;
            font-weight: 500;
            color: #555861;
            cursor: pointer;
        }

        .remember-label input {
            width: 15px;
            height: 15px;
            accent-color: var(--tap-cyan);
            cursor: pointer;
        }

        .forgot-link,
        .signup-link {
            color: #079FA5;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .forgot-link:hover,
        .signup-link:hover {
            color: var(--tap-dark);
            text-decoration: underline;
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

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 23px 0;
            color: #A0A2A9;
            font-size: 11px;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #E9E9ED;
        }

        .google-button {
            width: 100%;
            height: 48px;
            border: 1px solid #DCDDE2;
            border-radius: 8px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 11px;
            color: #34363D;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s, border-color .2s;
        }

        .google-button:hover {
            background: #F9FAFB;
            border-color: #BFC2C9;
        }

        .google-icon {
            width: 19px;
            height: 19px;
        }

        .auth-signup {
            text-align: center;
            margin-top: 27px;
            color: #858991;
            font-size: 12px;
        }

        .auth-footer {
            color: #9A9CA3;
            font-size: 11px;
        }

        /* SESSION STATUS */

        .session-status {
            padding: 12px 14px;
            margin-bottom: 22px;
            border-radius: 8px;
            background: rgba(8, 213, 219, .09);
            border: 1px solid rgba(8, 213, 219, .25);
            color: #087F84;
            font-size: 13px;
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
            background: linear-gradient(
                145deg,
                #48F0EC 0%,
                #08D5DB 45%,
                #079FA5 100%
            );
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
            background: linear-gradient(
                135deg,
                rgba(255,255,255,.45),
                transparent 45%
            );
        }

        .orb-reflection {
            position: absolute;
            width: 280px;
            height: 115px;
            top: calc(50% + 90px);
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(
                to bottom,
                rgba(8, 213, 219, .42),
                rgba(8, 213, 219, .10) 55%,
                transparent
            );
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
            background: linear-gradient(
                90deg,
                transparent,
                rgba(8, 213, 219, .5),
                rgba(8, 213, 219, .8),
                rgba(8, 213, 219, .5),
                transparent
            );
            z-index: 3;
        }

        .orb-glow {
            position: absolute;
            width: 460px;
            height: 460px;
            border-radius: 50%;
            background: radial-gradient(
                circle,
                rgba(8, 213, 219, .13) 0%,
                rgba(8, 213, 219, .05) 35%,
                transparent 70%
            );
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
            0%, 100% {
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
                padding: 45px 0;
            }

            .auth-heading h1 {
                font-size: 32px;
            }

            .auth-options {
                align-items: flex-start;
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

    <!-- LEFT: LOGIN FORM -->
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
                <h1>Welcome back</h1>
                <p>Welcome back! Please enter your details.</p>
            </div>

            @if (session('status'))
                <div class="session-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- EMAIL -->
                <div class="auth-form-group">
                    <label for="email" class="auth-label">
                        Email
                    </label>

                    <input
                        id="email"
                        class="auth-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        required
                        autofocus
                        autocomplete="username"
                    >

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
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >

                    @error('password')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- REMEMBER ME / FORGOT PASSWORD -->
                <div class="auth-options">

                    <label for="remember_me" class="remember-label">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                        >
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="forgot-link"
                        >
                            Forgot password?
                        </a>
                    @endif

                </div>

                <!-- SUBMIT -->
                <button type="submit" class="auth-submit">
                    Sign in <span>→</span>
                </button>

                <!-- GOOGLE LOGIN: ONLY SHOW IF CONFIGURED -->
                @if (Route::has('google.redirect'))
                    <div class="auth-divider">OR</div>

                    <a href="{{ route('google.redirect') }}" class="google-button">
                        <svg class="google-icon" viewBox="0 0 48 48" aria-hidden="true">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.93c-.58 2.96-2.26 5.48-4.76 7.18l7.73 6C44.42 37.98 46.98 31.83 46.98 24.55z"/>
                            <path fill="#FBBC05" d="M10.53 28.59A14.5 14.5 0 0 1 9.75 24c0-1.59.27-3.13.76-4.59l-7.98-6.19A23.9 23.9 0 0 0 0 24c0 3.87.93 7.52 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.9-5.8l-7.73-6c-2.15 1.45-4.92 2.3-8.17 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                        Sign in with Google
                    </a>
                @endif

            </form>

            <!-- REGISTRATION -->
            @if (Route::has('register'))
                <div class="auth-signup">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="signup-link">
                        Sign up
                    </a>
                </div>
            @endif

        </div>

        <footer class="auth-footer">
           Rideve © TapCard {{ date('Y') }}. All rights reserved.
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
