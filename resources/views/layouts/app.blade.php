<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trim($__env->yieldContent('title', 'Dashboard')) }} | TapCard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --tap-dark: #ffffff;
            --tap-dark-soft: #111114;
            --tap-panel: #151519;
            --tap-panel-light: #1B1B21;
            --tap-cyan: #08D5DB;
            --tap-lime: #B5FF58;
            --tap-muted: #92939D;
            --tap-border: rgba(255,255,255,.09);
            --tap-background: #08090B;
            --tap-white: #FFFFFF;
            --tap-radius: 20px;
            --tap-shadow: 0 15px 45px rgba(0,0,0,.2);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--tap-background);
            color: #F5F5F7;
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        a {
            color: inherit;
        }

        [x-cloak] {
            display: none !important;
        }

        .tap-app {
            min-height: 100vh;
            width: 100%;
            padding: 18px;
            background:
                radial-gradient(
                    circle at 90% 0%,
                    rgba(8,213,219,.055),
                    transparent 28%
                ),
                #08090B;
        }

        .tap-shell {
            width: 100%;
            max-width: 1800px;
            min-height: calc(100vh - 36px);
            margin: 0 auto;
        }

        .tap-main {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 32px 0 20px;
        }

        .tap-page-heading {
            margin-bottom: 25px;
        }

        .tap-page-heading h1 {
            margin: 0;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 600;
            letter-spacing: -1.5px;
            line-height: 1.15;
        }

        .tap-page-heading p {
            margin: 8px 0 0;
            color: var(--tap-muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .tap-panel {
            min-width: 0;
            padding: 23px;
            border: 1px solid var(--tap-border);
            border-radius: var(--tap-radius);
            background: var(--tap-panel);
            box-shadow: var(--tap-shadow);
        }

        .tap-panel-dark {
            color: #fff;
            background: #050506;
            border-color: rgba(255,255,255,.08);
        }

        .tap-panel-title {
            margin: 0;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -.4px;
        }

        .tap-muted {
            color: var(--tap-muted);
        }

        .tap-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 43px;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 10px;
            background: var(--tap-cyan);
            color: #08090B;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .tap-button:hover {
            background: #48E9EC;
            transform: translateY(-1px);
        }

        .tap-button-light {
            border-color: var(--tap-border);
            background: #1B1B21;
            color: #fff;
        }

        .tap-button-light:hover {
            background: #25252C;
            color: #08D5DB;
        }

        .tap-icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 39px;
            height: 39px;
            border: 1px solid var(--tap-border);
            border-radius: 10px;
            background: #1B1B21;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .tap-icon-button:hover {
            border-color: var(--tap-cyan);
            color: var(--tap-cyan);
        }

        .tap-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(8,213,219,.12);
            color: var(--tap-cyan);
            font-size: 10px;
            font-weight: 700;
        }

        .tap-badge-green {
            background: rgba(181,255,88,.1);
            color: var(--tap-lime);
        }

        .tap-divider {
            height: 1px;
            border: 0;
            background: var(--tap-border);
        }

        .tap-empty {
            padding: 32px 18px;
            color: var(--tap-muted);
            font-size: 13px;
            text-align: center;
        }

        .tap-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            margin: 0 auto 13px;
            border-radius: 15px;
            background: rgba(8,213,219,.1);
            color: var(--tap-cyan);
            font-size: 21px;
        }

        .tap-alert {
            margin-bottom: 18px;
            padding: 14px 17px;
            border: 1px solid rgba(8,213,219,.25);
            border-radius: 12px;
            background: rgba(8,213,219,.08);
            color: #54E4E7;
            font-size: 13px;
        }

        .tap-alert-error {
            border-color: rgba(248,113,113,.25);
            background: rgba(248,113,113,.08);
            color: #FCA5A5;
        }

        .tap-footer {
            padding: 24px 5px 8px;
            color: #696A73;
            font-size: 11px;
            text-align: center;
        }

        /* Form styling */
        .tap-shell input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        .tap-shell select,
        .tap-shell textarea {
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 10px;
            background: #101014;
            color: #fff;
        }

        .tap-shell input::placeholder,
        .tap-shell textarea::placeholder {
            color: #777883;
        }

        .tap-shell input:focus,
        .tap-shell select:focus,
        .tap-shell textarea:focus {
            border-color: var(--tap-cyan);
            outline: none;
            --tw-ring-color: rgba(8,213,219,.2);
        }

        .tap-shell label {
            color: #D8D8DE;
            font-size: 13px;
            font-weight: 600;
        }

        .tap-shell .text-gray-900,
        .tap-shell .text-gray-800 {
            color: #F5F5F7;
        }

        .tap-shell .text-gray-600,
        .tap-shell .text-gray-500 {
            color: #92939D;
        }

        .tap-shell .bg-white {
            background-color: #151519;
        }

        .tap-shell .border-gray-100,
        .tap-shell .border-gray-200 {
            border-color: rgba(255,255,255,.09);
        }

        .tap-shell .shadow-sm {
            box-shadow: var(--tap-shadow);
        }

        .tap-shell .rounded-lg,
        .tap-shell .rounded-md {
            border-radius: 12px;
        }

        @media (max-width: 900px) {
            .tap-app {
                padding: 12px;
            }

            .tap-main {
                padding-top: 25px;
            }
        }

        @media (max-width: 600px) {
            .tap-app {
                padding: 0;
            }

            .tap-shell {
                min-height: 100vh;
            }

            .tap-main {
                padding: 23px 0 15px;
            }

            .tap-panel {
                padding: 17px;
                border-radius: 16px;
            }

            .tap-page-heading {
                margin-bottom: 19px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="tap-app">
        <div class="tap-shell">

            @include('layouts.navigation')

            @isset($header)
                <div class="tap-main">
                    {{ $header }}
                </div>
            @endisset

            <main class="tap-main">
                @if (session('success'))
                    <div class="tap-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="tap-alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="tap-alert tap-alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Traditional Blade layout --}}
                @yield('content')

                {{-- Breeze component layout --}}
                {{ $slot ?? '' }}
            </main>

            <footer class="tap-footer">
                © {{ date('Y') }} TapCard By Rideve. Your identity. One tap away.
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>
</html>