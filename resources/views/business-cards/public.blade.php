@php
/*
|--------------------------------------------------------------------------
| TapCard public profile configuration
|--------------------------------------------------------------------------
*/

$fullName = trim(
$businessCard->first_name . ' ' . ($businessCard->last_name ?? '')
);

$profilePhoto = \App\Support\StorageUrl::url($businessCard->profile_photo);
$coverPhoto = \App\Support\StorageUrl::url($businessCard->cover_photo);

$initials = strtoupper(
substr($businessCard->first_name ?? 'T', 0, 1) .
substr($businessCard->last_name ?? 'C', 0, 1)
);

$whatsappNumber = preg_replace(
'/\D+/',
'',
$businessCard->whatsapp ?: $businessCard->phone
);

$websiteUrl = $businessCard->website;

if ($websiteUrl && !preg_match('/^https?:\/\//i', $websiteUrl)) {
$websiteUrl = 'https://' . $websiteUrl;
}

$shareUrl = route('cards.public', ['slug' => $businessCard->slug]);

$shareTitle = $fullName . ' | TapCard';

$shareText = 'Connect with ' . $fullName .
($businessCard->job_title ? ' - ' . $businessCard->job_title : '') .
'. View my digital business card.';

$location = collect([
$businessCard->address,
$businessCard->city,
$businessCard->country,
])->filter()->implode(', ');

$visibleSocialLinks = $businessCard->socialLinks
->where('is_visible', true)
->sortBy('sort_order');

$mapUrl = $location
? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($location)
: null;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#151419">

    <title>{{ $fullName }} | TapCard</title>

    <meta name="description"
        content="{{ \Illuminate\Support\Str::limit($businessCard->bio ?: $fullName . ' - Digital Business Card', 160) }}">

    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $fullName }} | TapCard">
    <meta property="og:description"
        content="{{ \Illuminate\Support\Str::limit($businessCard->bio ?: 'Connect with ' . $fullName, 160) }}">
    <meta property="og:url" content="{{ $shareUrl }}">

    @if($profilePhoto)
    <meta property="og:image" content="{{ $profilePhoto }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --tc-bg: #151419;
            --tc-dark: #101015;
            --tc-panel: #1d1c22;
            --tc-white: #ffffff;
            --tc-text: #17171b;
            --tc-muted: #777781;
            --tc-border: #e9e9ed;

            --tc-cyan: #08D5DB;
            --tc-lime: #B5FF58;
            --tc-orange: #FF5B19;
            --tc-yellow: #FFEB00;

            --tc-display: 'Outfit', sans-serif;
            --tc-body: 'DM Sans', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            padding: 30px 15px;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(8, 213, 219, .08), transparent 45%),
                var(--tc-bg);
            color: var(--tc-text);
            font-family: var(--tc-body);
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
        }

        .page-container {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
        }

        /* Main card */

        .profile-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 30px;
            background: var(--tc-white);
            box-shadow: 0 30px 90px rgba(0, 0, 0, .35);
        }

        /* Dark brand header */

        .profile-header {
            position: relative;
            display: flex;
            min-height: 285px;
            flex-direction: column;
            align-items: center;
            padding: 30px 22px 90px;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(8, 213, 219, .15), transparent 55%),
                linear-gradient(145deg, #202027, #111116 75%);
            color: white;
            text-align: center;
        }

        .profile-header.has-cover {
            background-color: var(--tc-dark);
            background-position: center;
            background-size: cover;
        }

        .profile-header.has-cover::before {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                    rgba(15, 15, 20, .72),
                    rgba(15, 15, 20, .88));
            content: "";
        }

        .header-decoration {
            position: absolute;
            top: -130px;
            right: -100px;
            width: 280px;
            height: 280px;
            border: 1px solid rgba(8, 213, 219, .12);
            border-radius: 50%;
            pointer-events: none;
        }

        .header-decoration::before,
        .header-decoration::after {
            position: absolute;
            border: 1px solid rgba(8, 213, 219, .08);
            border-radius: 50%;
            content: "";
        }

        .header-decoration::before {
            inset: 25px;
        }

        .header-decoration::after {
            inset: 55px;
        }

        .header-brand {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .tapcard-symbol {
            display: grid;
            width: 38px;
            height: 38px;
            grid-template-columns: repeat(2, 1fr);
            gap: 4px;
            transform: rotate(-8deg);
        }

        .tapcard-symbol span {
            display: block;
            border-radius: 50%;
        }

        .tapcard-symbol span:nth-child(1) {
            background: var(--tc-cyan);
        }

        .tapcard-symbol span:nth-child(2) {
            background: var(--tc-lime);
        }

        .tapcard-symbol span:nth-child(3) {
            background: var(--tc-orange);
        }

        .tapcard-symbol span:nth-child(4) {
            background: var(--tc-yellow);
        }

        .brand-company {
            color: white;
            font-family: var(--tc-display);
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .brand-company small {
            display: block;
            margin-top: 4px;
            color: rgba(255, 255, 255, .55);
            font-family: var(--tc-body);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2.5px;
        }

        .header-caption {
            position: relative;
            z-index: 2;
            margin-top: 19px;
            color: rgba(255, 255, 255, .55);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Profile image */

        .profile-area {
            position: relative;
            z-index: 3;
            display: flex;
            justify-content: center;
            margin-top: -100px;
        }

        .profile-photo {
            display: grid;
            width: 154px;
            height: 154px;
            flex-shrink: 0;
            place-items: center;
            overflow: hidden;
            border: 6px solid white;
            border-radius: 50%;
            background: linear-gradient(135deg, #292831, #151419);
            color: var(--tc-cyan);
            font-family: var(--tc-display);
            font-size: 44px;
            font-weight: 800;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .14);
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Profile content */

        .profile-content {
            position: relative;
            padding: 15px 30px 28px;
            text-align: center;
        }

        .profile-name {
            color: var(--tc-text);
            font-family: var(--tc-display);
            font-size: clamp(25px, 7vw, 31px);
            font-weight: 700;
            letter-spacing: -.8px;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .profile-position {
            margin-top: 8px;
            color: #33333b;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.5;
        }

        .profile-company {
            color: var(--tc-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .profile-bio {
            max-width: 340px;
            margin: 17px auto 0;
            color: #55555e;
            font-size: 14px;
            line-height: 1.8;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .profile-tagline {
            margin-top: 9px;
            color: #08D5DB;
            font-family: var(--tc-display);
            font-size: 15px;
            font-weight: 700;
        }

        /* Main buttons */

        .main-actions {
            display: grid;
            gap: 11px;
            margin-top: 24px;
        }

        .main-action {
            display: flex;
            width: 100%;
            min-height: 56px;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 13px 20px;
            border: 1.5px solid transparent;
            border-radius: 50px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            transition: .25s ease;
        }

        .main-action svg {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
        }

        .main-action-save {
            background: var(--tc-bg);
            color: white;
        }

        .main-action-save:hover {
            transform: translateY(-2px);
            background: #292831;
            box-shadow: 0 8px 20px rgba(21, 20, 25, .16);
        }

        .main-action-share {
            border-color: #bfc0c5;
            background: white;
            color: var(--tc-text);
        }

        .main-action-share:hover {
            transform: translateY(-2px);
            border-color: var(--tc-cyan);
            color: #087f83;
        }

        /* Quick contact shortcuts */

        .quick-contacts {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-top: 27px;
        }

        .quick-contact {
            display: flex;
            min-width: 0;
            flex-direction: column;
            align-items: center;
            gap: 9px;
            color: var(--tc-text);
            text-align: center;
            transition: transform .2s ease;
        }

        .quick-contact:hover {
            transform: translateY(-4px);
        }

        .quick-contact-icon {
            display: grid;
            width: 60px;
            height: 60px;
            place-items: center;
            border: 1px solid #e6e6eb;
            border-radius: 50%;
            background: #fff;
            color: #08D5DB;
            transition: .2s ease;
        }

        .quick-contact:hover .quick-contact-icon {
            border-color: var(--tc-cyan);
            background: rgba(8, 213, 219, .06);
            color: #087f83;
        }

        .quick-contact-icon svg {
            width: 25px;
            height: 25px;
            stroke-width: 1.8;
        }

        .quick-contact-label {
            font-size: 11px;
            font-weight: 700;
        }

        /* Social links */

        .social-section {
            position: relative;
            margin-top: 35px;
            padding: 23px 15px 5px;
            border: 1px solid #ececf0;
            border-radius: 20px;
        }

        .social-heading {
            position: absolute;
            top: -11px;
            left: 50%;
            padding: 0 13px;
            transform: translateX(-50%);
            background: white;
            color: #55555e;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .social-links {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            justify-items: center;
        }

        .social-link {
            display: grid;
            width: 52px;
            height: 52px;
            place-items: center;
            border-radius: 12px;
            background: #151419;
            color: white;
            transition: .25s ease;
        }

        .social-link:hover {
            transform: translateY(-4px);
            background: #292831;
            box-shadow: 0 7px 18px rgba(0, 0, 0, .14);
        }

        .social-link svg {
            width: 27px;
            height: 27px;
        }

        .social-letter {
            font-family: var(--tc-display);
            font-size: 22px;
            font-weight: 800;
        }

        /* Footer */

        .profile-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 27px;
            padding-top: 17px;
            border-top: 1px solid #eeeeef;
            color: #96969e;
            font-size: 10px;
        }

        .footer-brand {
            color: #151419;
            font-family: var(--tc-display);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: -.4px;
        }

        .footer-brand span {
            color: #079fa4;
        }

        .footer-symbol {
            display: inline-grid;
            width: 13px;
            height: 13px;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5px;
            transform: rotate(-8deg);
        }

        .footer-symbol span {
            border-radius: 50%;
        }

        .footer-symbol span:nth-child(1) {
            background: var(--tc-cyan);
        }

        .footer-symbol span:nth-child(2) {
            background: var(--tc-lime);
        }

        .footer-symbol span:nth-child(3) {
            background: var(--tc-orange);
        }

        .footer-symbol span:nth-child(4) {
            background: var(--tc-yellow);
        }

        /* Share feedback */

        .share-feedback {
            margin-top: 13px;
            color: #168b55;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .share-feedback[hidden] {
            display: none;
        }

        /* Responsive */

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: var(--tc-bg);
            }

            .page-container {
                max-width: 100%;
            }

            .profile-card {
                min-height: 100vh;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .profile-header {
                min-height: 290px;
                padding-top: 25px;
            }

            .profile-content {
                padding-right: 24px;
                padding-left: 24px;
            }

            .profile-photo {
                width: 150px;
                height: 150px;
            }

            .quick-contacts {
                gap: 7px;
            }

            .quick-contact-icon {
                width: 57px;
                height: 57px;
            }

            .social-section {
                margin-right: -8px;
                margin-left: -8px;
            }
        }

        @media (max-width: 360px) {
            .profile-content {
                padding-right: 17px;
                padding-left: 17px;
            }

            .quick-contact-icon {
                width: 50px;
                height: 50px;
            }

            .quick-contact-label {
                font-size: 10px;
            }

            .social-link {
                width: 46px;
                height: 46px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <main class="page-container">
        <article class="profile-card">

            {{-- Branded header --}}
            <header
                class="profile-header {{ $coverPhoto ? 'has-cover' : '' }}"
                @if($coverPhoto)
                style="background-image: url('{{ $coverPhoto }}')"
                @endif>
                <div class="header-decoration"></div>

                <div class="header-brand">
                    <div class="tapcard-symbol" aria-label="TapCard">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                    <div class="brand-company">
                        {{ $businessCard->company_name ?: 'TAPCARD' }}
                        @if($businessCard->company_name)
                        <small>DIGITAL BUSINESS CARD</small>
                        @endif
                    </div>
                </div>

                @if(!$businessCard->company_name)
                <p class="header-caption">Your identity. One tap away.</p>
                @endif
            </header>

            <div class="profile-area">
                <div class="profile-photo">
                    @if($profilePhoto)
                    <img src="{{ $profilePhoto }}" alt="{{ $fullName }}">
                    @else
                    {{ $initials }}
                    @endif
                </div>
            </div>

            <div class="profile-content">

                {{-- Personal details --}}
                <section class="personal-details">
                    <h1 class="profile-name">{{ $fullName }}</h1>

                    @if($businessCard->job_title)
                    <p class="profile-position">
                        {{ $businessCard->job_title }}
                        @if($businessCard->company_name)
                        <span> at {{ $businessCard->company_name }}</span>
                        @endif
                    </p>
                    @elseif($businessCard->company_name)
                    <p class="profile-position">{{ $businessCard->company_name }}</p>
                    @endif

                    @if($businessCard->bio)
                    <div class="profile-bio">{{ $businessCard->bio }}</div>
                    @endif

                    <p class="profile-tagline">Let's connect!</p>
                </section>

                {{-- Main actions --}}
                <section class="main-actions">

                    <a
                        href="{{ route('cards.contact.download', ['slug' => $businessCard->slug]) }}"
                        class="main-action main-action-save">
                        <svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M19 8v6M16 11h6" />
                        </svg>
                        Save Contact
                    </a>

                    <button
                        type="button"
                        onclick="shareTapCard()"
                        class="main-action main-action-share">
                        <svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <path d="m8.7 10.7 6.6-3.4M8.7 13.3l6.6 3.4" />
                        </svg>
                        Share Contact
                    </button>
                </section>

                {{-- Quick contact shortcuts --}}
                <section class="quick-contacts" aria-label="Contact shortcuts">

                    @if($businessCard->show_phone && $businessCard->phone)
                    <a href="tel:{{ $businessCard->phone }}" class="quick-contact">
                        <span class="quick-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L9.06 10.9a16 16 0 0 0 4.04 4.04l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.58 2.8.7a2 2 0 0 1 1.72 2z" />
                            </svg>
                        </span>
                        <span class="quick-contact-label">Phone</span>
                    </a>
                    @elseif($businessCard->whatsapp)
                    <a href="https://wa.me/{{ $whatsappNumber }}"
                        target="_blank" rel="noopener noreferrer"
                        class="quick-contact">
                        <span class="quick-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z" />
                            </svg>
                        </span>
                        <span class="quick-contact-label">WhatsApp</span>
                    </a>
                    @endif

                    @if($businessCard->show_email && $businessCard->email)
                    <a href="mailto:{{ $businessCard->email }}" class="quick-contact">
                        <span class="quick-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="m3 7 9 6 9-6" />
                            </svg>
                        </span>
                        <span class="quick-contact-label">Email</span>
                    </a>
                    @endif

                    @if($websiteUrl)
                    <a href="{{ $websiteUrl }}"
                        target="_blank" rel="noopener noreferrer"
                        class="quick-contact">
                        <span class="quick-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M2 12h20" />
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                        </span>
                        <span class="quick-contact-label">Website</span>
                    </a>
                    @endif

                    @if($mapUrl)
                    <a href="{{ $mapUrl }}"
                        target="_blank" rel="noopener noreferrer"
                        class="quick-contact">
                        <span class="quick-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" />
                                <circle cx="12" cy="10" r="2.5" />
                            </svg>
                        </span>
                        <span class="quick-contact-label">Location</span>
                    </a>
                    @endif

                </section>

                {{-- Social media --}}
                @if($visibleSocialLinks->isNotEmpty())
                <section class="social-section">
                    <h2 class="social-heading">Connect With Me</h2>

                    <div class="social-links">
                        @foreach($visibleSocialLinks as $socialLink)
                        @php
                        $platform = strtolower($socialLink->platform);

                        $platformName = $platform === 'twitter'
                        ? 'X / Twitter'
                        : ucfirst($platform);

                        $socialLabel = $socialLink->label ?: $platformName;

                        $socialIconPaths = [
                        'linkedin' => '
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6Z" />
                        <rect x="2" y="9" width="4" height="12" />
                        <circle cx="4" cy="4" r="2" />',
                        'instagram' => '
                        <rect x="3" y="3" width="18" height="18" rx="5" />
                        <circle cx="12" cy="12" r="4" />
                        <circle cx="18" cy="6" r="1" />',
                        'facebook' => '
                        <path d="M15 3h-3a5 5 0 0 0-5 5v3H4v4h3v6h4v-6h3l1-4h-4V8a1 1 0 0 1 1-1h3z" />',
                        'tiktok' => '
                        <path d="M14 3v12a4 4 0 1 1-4-4" />
                        <path d="M14 3c1 4 3 5 6 5" />',
                        'youtube' => '
                        <rect x="2" y="5" width="20" height="14" rx="4" />
                        <path d="m10 9 5 3-5 3z" />',
                        'twitter' => '
                        <path d="M22 5.9a8 8 0 0 1-2.4.7 4.2 4.2 0 0 0 1.8-2.3 8.3 8.3 0 0 1-2.7 1 4.2 4.2 0 0 0-7.2 3.8 11.9 11.9 0 0 1-8.6-4.4 4.2 4.2 0 0 0 1.3 5.6 4.2 4.2 0 0 1-1.9-.5v.1a4.2 4.2 0 0 0 3.4 4.1 4.2 4.2 0 0 1-1.9.1 4.2 4.2 0 0 0 3.9 2.9A8.4 8.4 0 0 1 2 18.7a11.8 11.8 0 0 0 18.2-10c0-.2 0-.4-.1-.5A8.4 8.4 0 0 0 22 5.9z" />',
                        'whatsapp' => '
                        <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z" />',
                        ];

                        $iconPath = $socialIconPaths[$platform] ?? null;
                        @endphp

                        <a
                            href="{{ route('cards.social.redirect', [
                                    'slug' => $businessCard->slug,
                                    'socialLink' => $socialLink->id
                                ]) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="social-link"
                            aria-label="{{ $socialLabel }}"
                            title="{{ $socialLabel }}">
                            @if($iconPath)
                            <svg viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                {!! $iconPath !!}
                            </svg>
                            @else
                            <span class="social-letter">
                                {{ strtoupper(substr($platform, 0, 1)) }}
                            </span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- Share feedback --}}
                <p id="shareFeedback"
                    class="share-feedback"
                    role="status"
                    aria-live="polite"
                    hidden></p>

                {{-- Footer --}}
                <footer class="profile-footer">
                    <span>Your identity. One tap away.</span>
                    <span>·</span>
                    <span class="footer-symbol" aria-hidden="true">
                        <span></span><span></span><span></span><span></span>
                    </span>
                    <a href="{{ url('/') }}" class="footer-brand">
                        TAP<span>CARD</span>
                    </a>
                </footer>

            </div>
        </article>
    </main>

    <script>
        const tapCardShareData = {
            title: @json($shareTitle),
            text: @json($shareText),
            url: @json($shareUrl)
        };

        function showShareFeedback(message) {
            const feedback = document.getElementById('shareFeedback');

            if (!feedback) return;

            feedback.textContent = message;
            feedback.hidden = false;

            clearTimeout(window.tapCardFeedbackTimer);

            window.tapCardFeedbackTimer = setTimeout(() => {
                feedback.hidden = true;
            }, 3000);
        }

        async function copyTapCardLink() {
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(tapCardShareData.url);
                } else {
                    const input = document.createElement('textarea');

                    input.value = tapCardShareData.url;
                    input.style.position = 'fixed';
                    input.style.opacity = '0';

                    document.body.appendChild(input);
                    input.select();

                    const copied = document.execCommand('copy');
                    input.remove();

                    if (!copied) {
                        throw new Error('Copy failed');
                    }
                }

                showShareFeedback('Your TapCard link has been copied!');
            } catch (error) {
                showShareFeedback('Unable to copy automatically. Please copy the link manually.');
            }
        }

        async function shareTapCard() {
            if (navigator.share) {
                try {
                    await navigator.share(tapCardShareData);
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        showShareFeedback('Unable to open the sharing menu.');
                    }
                }
            } else {
                await copyTapCardLink();
            }
        }
    </script>

</body>

</html>