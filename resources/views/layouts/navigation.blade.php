@php
$navWorkspace = $workspace ?? ($activeWorkspace ?? null);
$hasWorkspace = $navWorkspace && isset($navWorkspace->id);

$user = Auth::user();

$userInitials = collect(explode(' ', trim($user->name ?? 'User')))
->filter()
->take(2)
->map(fn ($part) => strtoupper(substr($part, 0, 1)))
->implode('');
@endphp

<nav
    class="tap-navigation"
    x-data="{ mobileOpen: false, profileOpen: false }"
    aria-label="Main navigation">
    <style>
        .tap-navigation {
            position: relative;
            z-index: 100;
            display: grid;
            grid-template-columns: minmax(180px, 1fr) auto minmax(180px, 1fr);
            align-items: center;
            gap: 20px;
            width: 100%;
            min-height: 72px;
            padding: 10px 16px;
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 18px;
            background: #111114;
        }

        /* TAPCARD BRAND LOGO */
        .tap-nav-brand {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            color: var(--white, #f7f7f5);
            text-decoration: none;
            white-space: nowrap;
        }

        .tap-brand-symbol {
            width: 36px !important;
            height: 36px !important;
            min-width: 36px;
            display: grid !important;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 4px;
            flex-shrink: 0;
            transform: rotate(-8deg);
        }

        .tap-brand-symbol>span {
            display: block !important;
            width: 100%;
            height: 100%;
            min-width: 0;
            min-height: 0;
            border-radius: 50% !important;
            opacity: 1 !important;
            visibility: visible !important;
            background: #08D5DB !important;
        }

        .tap-brand-symbol>span:nth-child(1) {
            background: #08D5DB !important;
        }

        .tap-brand-symbol>span:nth-child(2) {
            background: #B5FF58 !important;
        }

        .tap-brand-symbol>span:nth-child(3) {
            background: #FF5B19 !important;
        }

        .tap-brand-symbol>span:nth-child(4) {
            background: #FFEB00 !important;
        }

        .tap-brand-name {
            display: inline-block;
            color: #F7F7F5;
            font-family: 'Outfit', sans-serif;
            font-size: 27px;
            font-weight: 700;
            letter-spacing: -1.5px;
            line-height: 1;
        }

        @media (max-width: 480px) {
            .tap-brand-symbol {
                width: 32px !important;
                height: 32px !important;
                min-width: 32px;
            }

            .tap-brand-name {
                font-size: 23px;
            }
        }

        .tap-nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 5px;
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: 12px;
            background: #19191E;
        }

        .tap-nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 39px;
            padding: 0 16px;
            border-radius: 9px;
            color: #92939D;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s ease;
        }

        .tap-nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .06);
        }

        .tap-nav-link.active {
            color: #08090B;
            background: #08D5DB;
        }

        .tap-nav-actions {
            display: flex;
            align-items: center;
            justify-self: end;
            gap: 9px;
        }

        .tap-nav-action,
        .tap-nav-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 39px;
            padding: 0 13px;
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 10px;
            background: #19191E;
            color: #D8D8DF;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .tap-nav-action:hover,
        .tap-nav-circle:hover {
            border-color: rgba(8, 213, 219, .35);
            color: #08D5DB;
        }

        .tap-nav-circle {
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
        }

        .tap-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 31px;
            height: 31px;
            border-radius: 50%;
            background: #08D5DB;
            color: #08090B;
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 800;
        }

        .tap-nav-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            z-index: 120;
            width: 245px;
            padding: 9px;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 15px;
            background: #19191E;
            box-shadow: 0 20px 55px rgba(0, 0, 0, .45);
        }

        .tap-dropdown-user {
            padding: 13px;
            margin-bottom: 6px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .tap-dropdown-user strong {
            display: block;
            color: #fff;
            font-size: 13px;
        }

        .tap-dropdown-user span {
            display: block;
            margin-top: 5px;
            color: #85858F;
            font-size: 11px;
            overflow-wrap: anywhere;
        }

        .tap-dropdown-link {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 11px 12px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #D5D5DC;
            font-size: 12px;
            text-align: left;
            text-decoration: none;
            cursor: pointer;
        }

        .tap-dropdown-link:hover {
            background: rgba(255, 255, 255, .06);
            color: #08D5DB;
        }

        .tap-dropdown-link.danger {
            color: #FF7777;
        }

        .tap-mobile-toggle {
            display: none;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 10px;
            background: #19191E;
            color: #fff;
            cursor: pointer;
        }

        .tap-mobile-menu {
            display: none;
        }

        [x-cloak] {
            display: none !important;
        }

        @media (max-width: 1050px) {
            .tap-navigation {
                grid-template-columns: 1fr auto;
            }

            .tap-nav-links {
                display: none;
            }

            .tap-nav-actions {
                grid-column: 2;
            }

            .tap-mobile-toggle {
                display: inline-flex;
            }

            .tap-nav-action {
                display: none;
            }

            .tap-mobile-menu {
                position: absolute;
                top: calc(100% + 10px);
                left: 0;
                right: 0;
                z-index: 110;
                display: block;
                padding: 12px;
                border: 1px solid rgba(255, 255, 255, .09);
                border-radius: 15px;
                background: #151519;
                box-shadow: 0 20px 45px rgba(0, 0, 0, .4);
            }

            .tap-mobile-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 13px;
                border-radius: 9px;
                color: #B7B7C0;
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
            }

            .tap-mobile-link:hover,
            .tap-mobile-link.active {
                background: rgba(8, 213, 219, .1);
                color: #08D5DB;
            }

            .tap-mobile-menu form button {
                width: 100%;
                border: 0;
                background: transparent;
                text-align: left;
            }
        }

        @media (max-width: 480px) {
            .tap-navigation {
                min-height: 62px;
                padding: 8px 10px;
            }

            .tap-logo-name {
                font-size: 19px;
            }
        }
    </style>

    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" class="tap-nav-brand">
        <span class="tap-brand-symbol" aria-hidden="true">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </span>

        <span class="tap-brand-name">TAPCARD</span>
    </a>
    {{-- Desktop navigation --}}
    <div class="tap-nav-links">
        <a
            href="{{ route('dashboard') }}"
            class="tap-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Overview
        </a>

        <a
            href="{{ route('workspaces.index') }}"
            class="tap-nav-link {{ request()->routeIs('workspaces.*') ? 'active' : '' }}">
            Workspaces
        </a>

        @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.index'))
        <a
            href="{{ route('business-cards.index', ['workspace' => $navWorkspace->id]) }}"
            class="tap-nav-link {{ request()->routeIs('business-cards.*') ? 'active' : '' }}">
            My Cards
        </a>
        @endif
    </div>

    {{-- Account actions --}}
    <div class="tap-nav-actions">
        <a href="{{ route('profile.edit') }}" class="tap-nav-action">
            <span>⚙</span>
            Settings
        </a>

        <div class="relative">
            <button
                type="button"
                class="tap-nav-circle"
                @click="profileOpen = !profileOpen"
                @click.outside="profileOpen = false"
                aria-label="Open profile menu"
                :aria-expanded="profileOpen.toString()">
                <span class="tap-avatar">{{ $userInitials ?: 'U' }}</span>
            </button>

            <div
                x-cloak
                x-show="profileOpen"
                x-transition.origin.top.right
                class="tap-nav-dropdown">
                <div class="tap-dropdown-user">
                    <strong>{{ $user->name }}</strong>
                    <span>{{ $user->email }}</span>
                </div>

                <a href="{{ route('profile.edit') }}" class="tap-dropdown-link">
                    <span>⚙</span>
                    Account settings
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="tap-dropdown-link danger">
                        <span>↗</span>
                        Sign out
                    </button>
                </form>
            </div>
        </div>

        <button
            type="button"
            class="tap-mobile-toggle"
            @click="mobileOpen = !mobileOpen"
            :aria-expanded="mobileOpen.toString()"
            aria-label="Toggle navigation">
            <svg x-show="!mobileOpen" width="21" height="21" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
            </svg>

            <svg x-cloak x-show="mobileOpen" width="21" height="21" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    {{-- Mobile navigation --}}
    <div
        x-cloak
        x-show="mobileOpen"
        x-transition
        @click.outside="mobileOpen = false"
        class="tap-mobile-menu">
        <a
            href="{{ route('dashboard') }}"
            class="tap-mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span>⌂</span> Overview
        </a>

        <a
            href="{{ route('workspaces.index') }}"
            class="tap-mobile-link {{ request()->routeIs('workspaces.*') ? 'active' : '' }}">
            <span>▦</span> Workspaces
        </a>

        @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.index'))
        <a
            href="{{ route('business-cards.index', ['workspace' => $navWorkspace->id]) }}"
            class="tap-mobile-link {{ request()->routeIs('business-cards.*') ? 'active' : '' }}">
            <span>▣</span> My Cards
        </a>
        @endif

        <a href="{{ route('profile.edit') }}" class="tap-mobile-link">
            <span>⚙</span> Account Settings
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="tap-mobile-link">
                <span>↗</span> Sign Out
            </button>
        </form>
    </div>
</nav>