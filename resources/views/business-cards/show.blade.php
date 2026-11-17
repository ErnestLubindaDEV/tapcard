
@php
    /*
    |--------------------------------------------------------------------------
    | Theme configuration
    |--------------------------------------------------------------------------
    */

    $theme = $businessCard->theme;
    $colors = $theme?->colors ?? [];
    $settings = $theme?->settings ?? [];

    $background = $colors['background'] ?? '#F8FAFC';
    $surface = $colors['surface'] ?? '#FFFFFF';
    $primary = $colors['primary'] ?? '#2563EB';
    $text = $colors['text'] ?? '#172554';
    $muted = $colors['muted'] ?? '#64748B';

    $borderRadius = $settings['border_radius'] ?? '20px';
    $font = $settings['font'] ?? 'DM Sans, sans-serif';

    /*
    |--------------------------------------------------------------------------
    | Business card information
    |--------------------------------------------------------------------------
    */

    $fullName = trim(
        $businessCard->first_name . ' ' . ($businessCard->last_name ?? '')
    );

    $profilePhoto = \App\Support\StorageUrl::url(
        $businessCard->profile_photo
    );

    $coverPhoto = \App\Support\StorageUrl::url(
        $businessCard->cover_photo
    );

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

    /*
    |--------------------------------------------------------------------------
    | Sharing information
    |--------------------------------------------------------------------------
    */

    $shareUrl = route('cards.public', [
        'slug' => $businessCard->slug,
    ]);

    $shareTitle = $fullName . ' | TapCard';

    $shareText = 'Connect with ' . $fullName .
        ($businessCard->job_title ? ' - ' . $businessCard->job_title : '') .
        '. View my digital business card.';

    /*
    |--------------------------------------------------------------------------
    | Workspace poster information
    |--------------------------------------------------------------------------
    */

    $workspaceName = $workspace->name ?? 'Your Workspace';

    $workspaceWebsite = $workspace->getAttribute('website');
    $workspaceEmail = $workspace->getAttribute('email');
    $workspacePhone = $workspace->getAttribute('phone');

    $workspaceAddress = collect([
        $workspace->getAttribute('address'),
        $workspace->getAttribute('city'),
        $workspace->getAttribute('country'),
    ])->filter()->implode(', ');

    $workspaceDescription = $workspace->getAttribute('description');

    $workspaceLogoPath = $workspace->getAttribute('logo')
        ?: $workspace->getAttribute('logo_path');

    $workspaceLogo = $workspaceLogoPath
        ? \App\Support\StorageUrl::url($workspaceLogoPath)
        : null;

    if (
        $workspaceWebsite &&
        !preg_match('/^https?:\/\//i', $workspaceWebsite)
    ) {
        $workspaceWebsite = 'https://' . $workspaceWebsite;
    }

    $posterWebsite = $workspaceWebsite ?: $shareUrl;

    $posterWebsiteLabel = parse_url(
        $posterWebsite,
        PHP_URL_HOST
    ) ?: $posterWebsite;

    $posterSocialLinks = $businessCard->socialLinks
        ->where('is_visible', true)
        ->sortBy('sort_order')
        ->take(4);

@endphp

<x-app-layout>

    {{-- ==========================================================
        PAGE HEADER
    =========================================================== --}}

    <x-slot name="header">

        <div class="card-detail-header">

            <div>

                <div class="card-detail-breadcrumb">

                    <a href="{{ route('workspaces.index') }}">
                        Workspaces
                    </a>

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                    <a href="{{ route('workspaces.show', $workspace) }}">
                        {{ $workspace->name }}
                    </a>

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                    <a href="{{ route('business-cards.index', $workspace) }}">
                        Business Cards
                    </a>

                </div>

                <span class="detail-eyebrow">
                    DIGITAL IDENTITY MANAGEMENT
                </span>

                <h2 class="detail-page-title">
                    {{ $businessCard->full_name }}
                </h2>

                <p class="detail-page-description">
                    Manage your digital business card and professional identity.
                </p>

            </div>

            <div class="detail-header-actions">

                <a
                    href="{{ route('business-cards.index', $workspace) }}"
                    class="detail-secondary-btn"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m15 18-6-6 6-6"/>
                        <path d="M9 12h12"/>
                    </svg>

                    All Business Cards
                </a>

                <a
                    href="{{ route('business-cards.edit', [$workspace, $businessCard]) }}"
                    class="detail-primary-btn"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 15l-.8 5Z"/>
                    </svg>

                    Edit Card
                </a>

            </div>

        </div>

    </x-slot>


    <div class="card-detail-page">

        <div class="card-detail-container">

            {{-- Notifications --}}

            @if(session('success'))

                <div class="detail-alert success">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            d="m5 12 4 4L19 6"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="detail-alert error">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m9 9 6 6m0-6-6 6" stroke-width="2"/>
                    </svg>

                    {{ session('error') }}

                </div>

            @endif


            {{-- ==================================================
                MAIN PROFILE CARD
            =================================================== --}}

            <section class="detail-profile-card">

                <div class="detail-cover">

                    @if($coverPhoto)

                        <img src="{{ $coverPhoto }}" alt="Cover photo">

                    @else

                        <div class="detail-cover-placeholder">

                            <div class="detail-cover-circle"></div>
                            <div class="detail-cover-circle second"></div>

                            <span>TAPCARD</span>

                        </div>

                    @endif

                    <div class="detail-cover-overlay"></div>

                    <div class="detail-cover-badges">

                        @if($businessCard->is_primary)

                            <span class="detail-badge primary">

                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="m12 2 3 6 7 .9-5 4.8 1.2 7L12 17.4 5.8 21l1.2-7L2 8.9 9 8z"/>
                                </svg>

                                Primary Card

                            </span>

                        @endif

                        <span class="detail-badge {{ $businessCard->status === 'published' ? 'published' : 'draft' }}">
                            <span></span>
                            {{ ucfirst($businessCard->status) }}
                        </span>

                    </div>

                </div>


                <div class="detail-profile-content">

                    <div class="detail-profile-top">

                        <div class="detail-profile-identity">

                            @if($profilePhoto)

                                <img
                                    src="{{ $profilePhoto }}"
                                    class="detail-avatar"
                                    alt="{{ $fullName }}"
                                >

                            @else

                                <div class="detail-avatar detail-avatar-placeholder">
                                    {{ $initials }}
                                </div>

                            @endif


                            <div class="detail-identity-text">

                                <span class="detail-eyebrow">
                                    PROFESSIONAL PROFILE
                                </span>

                                <h3>{{ $fullName }}</h3>

                                <p>

                                    {{ $businessCard->job_title ?: 'Professional' }}

                                    @if($businessCard->company_name)
                                        <span>at</span>
                                        {{ $businessCard->company_name }}
                                    @endif

                                </p>

                            </div>

                        </div>


                        <div class="detail-profile-tags">

                            <span class="detail-tag visibility">
                                {{ ucfirst($businessCard->visibility) }}
                            </span>

                            @if($businessCard->is_primary)

                                <span class="detail-tag primary">
                                    Primary
                                </span>

                            @endif

                        </div>

                    </div>


                    @if($businessCard->bio)

                        <div class="detail-profile-bio">
                            {{ $businessCard->bio }}
                        </div>

                    @endif


                    <div class="detail-profile-footer">

                        <div>
                            <span>DESIGN THEME</span>
                            <strong>{{ $businessCard->theme?->name ?? 'Default' }}</strong>
                        </div>

                        <div>
                            <span>WORKSPACE</span>
                            <strong>{{ $workspace->name }}</strong>
                        </div>

                        <div>
                            <span>CARD STATUS</span>

                            <strong class="{{ $businessCard->status === 'published' ? 'text-lime' : 'text-orange' }}">
                                {{ ucfirst($businessCard->status) }}
                            </strong>
                        </div>

                    </div>

                </div>

            </section>


            {{-- ==================================================
                INFORMATION PANELS
            =================================================== --}}

            <div class="detail-information-grid">

                {{-- Contact information --}}

                <section class="detail-information-card">

                    <div class="detail-section-heading">

                        <div class="detail-section-icon cyan">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                                <path d="m22 7-10 7L2 7"/>
                            </svg>

                        </div>

                        <div>

                            <span class="detail-eyebrow">GET IN TOUCH</span>
                            <h3>Contact information</h3>

                        </div>

                    </div>


                    <div class="detail-information-list">

                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="m3 7 9 6 9-6"/>
                                </svg>
                            </div>

                            <div>
                                <span>Email address</span>
                                <strong>{{ $businessCard->email ?: 'Not provided' }}</strong>
                            </div>

                        </div>


                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L9 10.7a16 16 0 0 0 4.3 4.3l1.3-1.3a2 2 0 0 1 2.1-.5c.8.3 1.7.6 2.6.7a2 2 0 0 1 1.7 2Z"/>
                                </svg>
                            </div>

                            <div>
                                <span>Phone number</span>
                                <strong>{{ $businessCard->phone ?: 'Not provided' }}</strong>
                            </div>

                        </div>


                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.7 8.7 0 0 1-4-.9L3 21l1.9-4.8a8.4 8.4 0 1 1 16.1-4.7Z"/>
                                </svg>
                            </div>

                            <div>
                                <span>WhatsApp</span>
                                <strong>{{ $businessCard->whatsapp ?: 'Not provided' }}</strong>
                            </div>

                        </div>


                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/>
                                </svg>
                            </div>

                            <div>
                                <span>Website</span>
                                <strong>{{ $businessCard->website ?: 'Not provided' }}</strong>
                            </div>

                        </div>

                    </div>

                </section>


                {{-- Professional information --}}

                <section class="detail-information-card">

                    <div class="detail-section-heading">

                        <div class="detail-section-icon lime">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 21h18M5 21V7l7-4 7 4v14"/>
                                <path d="M9 9h.01M9 12h.01M9 15h.01M15 9h.01M15 12h.01M15 15h.01"/>
                            </svg>

                        </div>

                        <div>
                            <span class="detail-eyebrow">PROFESSIONAL DETAILS</span>
                            <h3>Professional information</h3>
                        </div>

                    </div>


                    <div class="detail-information-list">

                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3" y="7" width="18" height="14" rx="2"/>
                                    <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18"/>
                                </svg>
                            </div>

                            <div>
                                <span>Company</span>
                                <strong>{{ $businessCard->company_name ?: 'Not provided' }}</strong>
                            </div>

                        </div>


                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="3.5"/>
                                    <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a1.7 1.7 0 0 1-2.4 2.4l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5v.2a1.7 1.7 0 0 1-3.4 0v-.2a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a1.7 1.7 0 0 1-2.4-2.4l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H4.5a1.7 1.7 0 0 1 0-3.4h.2a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a1.7 1.7 0 0 1 2.4-2.4l.1.1a1.7 1.7 0 0 0 1.8.3 1.7 1.7 0 0 0 1-1.5V4.5a1.7 1.7 0 0 1 3.4 0v.2a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a1.7 1.7 0 0 1 2.4 2.4l-.1.1a1.7 1.7 0 0 0-.3 1.8 1.7 1.7 0 0 0 1.5 1h.2a1.7 1.7 0 0 1 0 3.4h-.2a1.7 1.7 0 0 0-1.5.5Z"/>
                                </svg>
                            </div>

                            <div>
                                <span>Job title</span>
                                <strong>{{ $businessCard->job_title ?: 'Not provided' }}</strong>
                            </div>

                        </div>


                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                                    <path d="M8 8h8M8 12h8M8 16h5"/>
                                </svg>
                            </div>

                            <div>
                                <span>Card theme</span>
                                <strong>{{ $businessCard->theme?->name ?? 'Default' }}</strong>
                            </div>

                        </div>


                        <div class="detail-information-item">

                            <div class="detail-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="2.5"/>
                                </svg>
                            </div>

                            <div>
                                <span>Location</span>

                                <strong>
                                    {{ collect([$businessCard->city, $businessCard->country])->filter()->join(', ') ?: 'Not provided' }}
                                </strong>
                            </div>

                        </div>

                    </div>

                </section>

            </div>


            {{-- ==================================================
                BIOGRAPHY
            =================================================== --}}

            @if($businessCard->bio)

                <section class="detail-information-card detail-bio-card">

                    <div class="detail-section-heading">

                        <div class="detail-section-icon orange">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 5h16M4 10h16M4 15h10M4 20h10"/>
                            </svg>

                        </div>

                        <div>
                            <span class="detail-eyebrow">ABOUT THE PROFESSIONAL</span>
                            <h3>Biography</h3>
                        </div>

                    </div>

                    <p class="detail-bio-text">
                        {{ $businessCard->bio }}
                    </p>

                </section>

            @endif


            {{-- ==================================================
                TAPCARD SHARE POSTER
            =================================================== --}}

            @if($businessCard->status === 'published' && $businessCard->visibility === 'public')

                <section class="share-poster-section">

                    <div class="share-poster-heading">

                        <div>
                            <span class="detail-eyebrow">READY TO SHARE</span>

                            <h3>Your contact sharing poster</h3>

                            <p>
                                Let people scan your QR code and connect with you instantly.
                            </p>
                        </div>

                        <span class="share-poster-status">
                            <span></span>
                            Live QR Code
                        </span>

                    </div>


                    <div class="share-poster-layout">

                        {{-- POSTER PREVIEW --}}

                        <div class="share-poster-preview-wrapper">

                            <div id="tapcardSharePoster" class="tapcard-share-poster">

                                <div class="poster-decoration poster-decoration-one"></div>
                                <div class="poster-decoration poster-decoration-two"></div>


                                {{-- Brand --}}

                                <div class="poster-brand">

                                    @if($workspaceLogo)

                                        <img
                                            src="{{ $workspaceLogo }}"
                                            class="poster-workspace-logo"
                                            alt="{{ $workspaceName }}"
                                            crossorigin="anonymous"
                                        >

                                    @else

                                        <div class="poster-tapcard-symbol">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>

                                        <span class="poster-tapcard-name">
                                            TAPCARD By Rideve
                                        </span>

                                    @endif

                                   

                                </div>


                                {{-- Main message --}}

                                <div class="poster-main-message">

                                  

                                    <h2>
                                        SCAN TO<br>
                                        <span>CONNECT</span><br>
                                        WITH ME.
                                    </h2>

                                   

                                </div>


                                {{-- QR code --}}

                                <div class="poster-qr-container">

                                    <div class="poster-qr-frame">
                                        <div id="tapcardPosterQr" class="poster-qr-code"></div>
                                    </div>

                                    <span class="poster-qr-caption">
                                        SCAN WITH YOUR PHONE CAMERA
                                    </span>

                                </div>


                                {{-- Person details --}}

                                <div class="poster-person">

                                    <span class="poster-person-name">
                                        {{ $fullName }}
                                    </span>

                                    @if($businessCard->job_title)

                                        <span class="poster-person-title">
                                            {{ $businessCard->job_title }}
                                        </span>

                                    @endif

                                    @if($businessCard->company_name)

                                        <span class="poster-person-company">
                                            {{ $businessCard->company_name }}
                                        </span>

                                    @endif

                                </div>


                                {{-- Workspace information --}}



                                {{-- Footer --}}

                                <div class="poster-footer">

                                    <span class="poster-footer-symbol">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </span>

                                    <span>POWERED BY TAPCARD</span>

                                </div>

                            </div>

                        </div>


                        {{-- POSTER CONTROLS --}}

                        <div class="share-poster-controls">

                            <div class="poster-control-icon">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                    <path d="M14 14h3v3h-3zM20 14v2M14 20h2M20 20v1"/>
                                </svg>

                            </div>

                            <h4>Share your identity anywhere.</h4>

                            <p>
                                Download your personalised QR poster and share it on
                                WhatsApp, social media, email or print it for your office.
                            </p>


                            <div class="poster-information">

                                <div>
                                    <span>PROFILE</span>
                                    <strong>{{ $fullName }}</strong>
                                </div>

                                <div>
                                    <span>WORKSPACE</span>
                                    <strong>{{ $workspaceName }}</strong>
                                </div>

                                <div>
                                    <span>PUBLIC CARD URL</span>
                                    <strong class="poster-url-text">
                                        {{ $shareUrl }}
                                    </strong>
                                </div>

                            </div>


                            <button
                                type="button"
                                id="downloadTapCardPoster"
                                class="poster-download-btn"
                            >

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <path d="m7 10 5 5 5-5M12 15V3"/>
                                </svg>

                                Download Poster as PNG

                            </button>


                            <button
                                type="button"
                                id="shareTapCardPoster"
                                class="poster-share-btn"
                            >

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="18" cy="5" r="3"/>
                                    <circle cx="6" cy="12" r="3"/>
                                    <circle cx="18" cy="19" r="3"/>
                                    <path d="m8.7 10.7 6.6-3.4M8.7 13.3l6.6 3.4"/>
                                </svg>

                                Share Poster

                            </button>


                            <span
                                id="posterDownloadMessage"
                                class="poster-feedback"
                                aria-live="polite"
                            ></span>


                            <div class="poster-tip">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 16v-4m0-4h.01"/>
                                </svg>

                                Your QR code links directly to your published digital business card.

                            </div>

                        </div>

                    </div>

                </section>

            @endif


            {{-- ==================================================
                CARD ACTIONS
            =================================================== --}}

            <section class="detail-actions-card">

                <div class="detail-actions-heading">

                    <div>

                        <span class="detail-eyebrow">CARD CONTROL CENTRE</span>

                        <h3>Manage your card</h3>

                        <p>
                            Control publication, sharing, analytics and card settings.
                        </p>

                    </div>

                    <div class="detail-actions-heading-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 2v4m0 12v4m10-10h-4M6 12H2m17.1-7.1-2.8 2.8M7.7 16.3l-2.8 2.8m14.2 0-2.8-2.8M7.7 7.7 4.9 4.9"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>

                    </div>

                </div>


                {{-- Quick actions --}}

                <div class="detail-quick-actions">

                    <a
                        href="{{ route('business-cards.social-links.index', [
                            'workspace' => $workspace->id,
                            'businessCard' => $businessCard->id
                        ]) }}"
                        class="detail-quick-action"
                    >

                        <div class="quick-action-icon cyan">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M10 13a5 5 0 0 0 7.1 0l3-3a5 5 0 0 0-7.1-7.1l-1.7 1.7"/>
                                <path d="M14 11a5 5 0 0 0-7.1 0l-3 3a5 5 0 0 0 7.1 7.1l1.7-1.7"/>
                            </svg>

                        </div>

                        <div>
                            <strong>Social Links</strong>
                            <span>Manage your social profiles</span>
                        </div>

                        <svg class="quick-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>

                    </a>


                    <a
                        href="{{ route('business-cards.analytics.index', [
                            'workspace' => $workspace->id,
                            'businessCard' => $businessCard->id
                        ]) }}"
                        class="detail-quick-action"
                    >

                        <div class="quick-action-icon lime">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 3v18h18"/>
                                <path d="m7 14 4-4 4 3 6-7"/>
                            </svg>

                        </div>

                        <div>
                            <strong>Card Analytics</strong>
                            <span>Monitor card performance</span>
                        </div>

                        <svg class="quick-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>

                    </a>


                    @if($businessCard->status === 'published' && $businessCard->visibility === 'public')

                        <a
                            href="{{ route('cards.qr', ['slug' => $businessCard->slug]) }}"
                            class="detail-quick-action"
                        >

                            <div class="quick-action-icon orange">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                    <path d="M14 14h3v3h-3zM20 14v2M14 20h2M20 20v1"/>
                                </svg>

                            </div>

                            <div>
                                <strong>QR Code</strong>
                                <span>Generate and download QR</span>
                            </div>

                            <svg class="quick-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>

                        </a>


                        <a
                            href="{{ $businessCard->public_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="detail-quick-action"
                        >

                            <div class="quick-action-icon yellow">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                            </div>

                            <div>
                                <strong>Public Card</strong>
                                <span>View your live digital card</span>
                            </div>

                            <svg class="quick-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>

                        </a>

                    @endif

                </div>


                <div class="detail-actions-divider"></div>


                {{-- Publication controls --}}

                <div class="detail-action-group">

                    <div class="detail-action-group-heading">

                        <h4>Publication controls</h4>

                        <p>
                            Manage the visibility of your digital business card.
                        </p>

                    </div>


                    <div class="detail-action-buttons">

                        @if($businessCard->status === 'published')

                            <form
                                method="POST"
                                action="{{ route('business-cards.unpublish', [
                                    'workspace' => $workspace->id,
                                    'businessCard' => $businessCard->id
                                ]) }}"
                                onsubmit="return confirm('Are you sure you want to unpublish this card?')"
                            >

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="detail-action-btn warning">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <rect x="4" y="4" width="16" height="16" rx="3"/>
                                        <path d="M9 9h6v6H9z"/>
                                    </svg>

                                    Unpublish Card

                                </button>

                            </form>

                        @else

                            <form
                                method="POST"
                                action="{{ route('business-cards.publish', [
                                    'workspace' => $workspace->id,
                                    'businessCard' => $businessCard->id
                                ]) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="detail-action-btn success">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="m5 12 4 4L19 6"/>
                                    </svg>

                                    Publish Card

                                </button>

                            </form>

                        @endif


                        @if($businessCard->status === 'published' && $businessCard->visibility === 'public')

                            <a
                                href="{{ $businessCard->public_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="detail-action-btn cyan"
                            >

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                                View Public Card

                            </a>

                        @endif


                        <a
                            href="{{ route('business-cards.edit', [
                                'workspace' => $workspace->id,
                                'businessCard' => $businessCard->id
                            ]) }}"
                            class="detail-action-btn neutral"
                        >

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 15l-.8 5Z"/>
                            </svg>

                            Edit Card

                        </a>


                        @unless($businessCard->is_primary)

                            <form
                                method="POST"
                                action="{{ route('business-cards.primary', [
                                    'workspace' => $workspace->id,
                                    'businessCard' => $businessCard->id
                                ]) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="detail-action-btn primary">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="m12 2 3 6 7 .9-5 4.8 1.2 7L12 17.4 5.8 21l1.2-7L2 8.9 9 8z"/>
                                    </svg>

                                    Set as Primary

                                </button>

                            </form>

                        @endunless

                    </div>

                </div>


                {{-- Danger zone --}}

                <div class="detail-danger-zone">

                    <div>

                        <span>DANGER ZONE</span>

                        <h4>Delete this business card</h4>

                        <p>
                            This action will remove the card from your workspace.
                        </p>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('business-cards.destroy', [
                            'workspace' => $workspace->id,
                            'businessCard' => $businessCard->id
                        ]) }}"
                        onsubmit="return confirm('Are you sure you want to delete this business card? This action cannot be undone.')"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="detail-delete-btn">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6"/>
                            </svg>

                            Delete Card

                        </button>

                    </form>

                </div>

            </section>

        </div>

    </div>


    {{-- ==========================================================
        STYLES
    =========================================================== --}}

    <style>

        :root {
            --detail-bg: #151419;
            --detail-panel: #1d1c22;
            --detail-panel-hover: #24232a;
            --detail-border: rgba(255,255,255,.075);
            --detail-text: #f7f7f5;
            --detail-muted: #96949e;
            --detail-cyan: #08D5DB;
            --detail-lime: #B5FF58;
            --detail-orange: #FF5B19;
            --detail-yellow: #FFEB00;
        }

        .card-detail-header,
        .card-detail-page {
            font-family: var(--font-body, 'DM Sans', sans-serif);
        }

        .card-detail-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
        }

        .card-detail-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 15px;
            color: var(--detail-muted);
            font-size: 10px;
        }

        .card-detail-breadcrumb a {
            color: var(--detail-muted);
            text-decoration: none;
        }

        .card-detail-breadcrumb a:hover {
            color: var(--detail-cyan);
        }

        .card-detail-breadcrumb svg {
            width: 12px;
            height: 12px;
        }

        .detail-eyebrow {
            display: block;
            margin-bottom: 6px;
            color: var(--detail-cyan);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.8px;
        }

        .detail-page-title {
            color: var(--detail-text) !important;
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: clamp(23px, 3vw, 29px);
            font-weight: 700;
            letter-spacing: -1px;
        }

        .detail-page-description {
            margin-top: 5px;
            color: var(--detail-muted);
            font-size: 11px;
        }

        .detail-header-actions {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
        }

        .detail-primary-btn,
        .detail-secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 0 14px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s ease;
        }

        .detail-primary-btn svg,
        .detail-secondary-btn svg {
            width: 15px;
            height: 15px;
        }

        .detail-primary-btn {
            border: 1px solid var(--detail-cyan);
            background: var(--detail-cyan);
            color: #151419 !important;
        }

        .detail-primary-btn:hover {
            background: #39e5e9;
            transform: translateY(-2px);
        }

        .detail-secondary-btn {
            border: 1px solid var(--detail-border);
            background: var(--detail-panel);
            color: var(--detail-text);
        }

        .detail-secondary-btn:hover {
            border-color: var(--detail-cyan);
            color: var(--detail-cyan);
        }

        .card-detail-page {
            min-height: calc(100vh - 100px);
            padding: 30px 0 65px;
            background: var(--detail-bg);
        }

        .card-detail-container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 26px;
        }

        /* Alerts */

        .detail-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding: 13px 15px;
            border: 1px solid;
            border-radius: 9px;
            font-size: 11px;
        }

        .detail-alert svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .detail-alert.success {
            border-color: rgba(181,255,88,.2);
            background: rgba(181,255,88,.07);
            color: var(--detail-lime);
        }

        .detail-alert.error {
            border-color: rgba(255,91,25,.2);
            background: rgba(255,91,25,.07);
            color: #ff9b78;
        }

        /* Profile */

        .detail-profile-card {
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid var(--detail-border);
            border-radius: 14px;
            background: var(--detail-panel);
        }

        .detail-cover {
            position: relative;
            height: 190px;
            overflow: hidden;
            background: #101015;
        }

        .detail-cover > img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-cover-placeholder {
            position: relative;
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 80% 20%, rgba(8,213,219,.2), transparent 50%),
                linear-gradient(135deg, #24232a, #101015);
        }

        .detail-cover-placeholder > span {
            z-index: 1;
            color: rgba(255,255,255,.18);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 8px;
        }

        .detail-cover-circle {
            position: absolute;
            top: -130px;
            right: -40px;
            width: 350px;
            height: 350px;
            border: 1px solid rgba(8,213,219,.16);
            border-radius: 50%;
        }

        .detail-cover-circle.second {
            top: -90px;
            right: 0;
            width: 270px;
            height: 270px;
            border-color: rgba(8,213,219,.1);
        }

        .detail-cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(21,20,25,.3));
            pointer-events: none;
        }

        .detail-cover-badges {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .detail-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border: 1px solid transparent;
            border-radius: 30px;
            background: rgba(21,20,25,.85);
            font-size: 9px;
            font-weight: 800;
            backdrop-filter: blur(10px);
        }

        .detail-badge svg {
            width: 11px;
            height: 11px;
        }

        .detail-badge.primary {
            border-color: rgba(255,235,0,.2);
            color: var(--detail-yellow);
        }

        .detail-badge.published {
            border-color: rgba(181,255,88,.2);
            color: var(--detail-lime);
        }

        .detail-badge.draft {
            border-color: rgba(255,91,25,.2);
            color: #ff9b78;
        }

        .detail-badge:not(.primary) span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .detail-profile-content {
            padding: 0 27px 23px;
        }

        .detail-profile-top {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-top: -43px;
        }

        .detail-profile-identity {
            display: flex;
            align-items: flex-end;
            gap: 17px;
            min-width: 0;
        }

        .detail-avatar {
            display: grid;
            width: 91px;
            height: 91px;
            flex-shrink: 0;
            place-items: center;
            border: 4px solid var(--detail-panel);
            border-radius: 17px;
            background: #292831;
            color: var(--detail-cyan);
            object-fit: cover;
        }

        .detail-avatar-placeholder {
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 33px;
            font-weight: 800;
        }

        .detail-identity-text {
            padding-bottom: 5px;
            min-width: 0;
        }

        .detail-identity-text h3 {
            overflow-wrap: anywhere;
            color: var(--detail-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -.6px;
        }

        .detail-identity-text p {
            margin-top: 5px;
            color: var(--detail-muted);
            font-size: 11px;
        }

        .detail-identity-text p span {
            color: var(--detail-cyan);
        }

        .detail-profile-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding-bottom: 6px;
        }

        .detail-tag {
            padding: 6px 9px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 800;
        }

        .detail-tag.visibility {
            background: rgba(8,213,219,.09);
            color: var(--detail-cyan);
        }

        .detail-tag.primary {
            background: rgba(255,235,0,.09);
            color: var(--detail-yellow);
        }

        .detail-profile-bio {
            max-width: 780px;
            margin-top: 20px;
            color: #a8a6af;
            font-size: 12px;
            line-height: 1.8;
        }

        .detail-profile-footer {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 23px;
            padding-top: 18px;
            border-top: 1px solid var(--detail-border);
        }

        .detail-profile-footer span {
            display: block;
            margin-bottom: 6px;
            color: #77757f;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .detail-profile-footer strong {
            color: var(--detail-text);
            font-size: 11px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .detail-profile-footer .text-lime {
            color: var(--detail-lime);
        }

        .detail-profile-footer .text-orange {
            color: var(--detail-orange);
        }

        /* Information cards */

        .detail-information-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 18px;
        }

        .detail-information-card {
            min-width: 0;
            padding: 23px;
            border: 1px solid var(--detail-border);
            border-radius: 12px;
            background: var(--detail-panel);
        }

        .detail-section-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 17px;
            border-bottom: 1px solid var(--detail-border);
        }

        .detail-section-icon {
            display: grid;
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 10px;
        }

        .detail-section-icon svg {
            width: 19px;
            height: 19px;
        }

        .detail-section-icon.cyan {
            background: rgba(8,213,219,.1);
            color: var(--detail-cyan);
        }

        .detail-section-icon.lime {
            background: rgba(181,255,88,.1);
            color: var(--detail-lime);
        }

        .detail-section-icon.orange {
            background: rgba(255,91,25,.1);
            color: var(--detail-orange);
        }

        .detail-section-heading .detail-eyebrow {
            margin-bottom: 4px;
        }

        .detail-section-heading h3 {
            color: var(--detail-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 15px;
            font-weight: 700;
        }

        .detail-information-list {
            display: flex;
            flex-direction: column;
        }

        .detail-information-item {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,.045);
        }

        .detail-information-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .detail-item-icon {
            display: grid;
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            place-items: center;
            border: 1px solid rgba(8,213,219,.1);
            border-radius: 8px;
            background: rgba(8,213,219,.05);
            color: var(--detail-cyan);
        }

        .detail-item-icon svg {
            width: 16px;
            height: 16px;
            stroke-width: 1.7;
        }

        .detail-information-item > div:last-child {
            min-width: 0;
        }

        .detail-information-item span {
            display: block;
            margin-bottom: 4px;
            color: #77757f;
            font-size: 9px;
        }

        .detail-information-item strong {
            display: block;
            overflow-wrap: anywhere;
            color: var(--detail-text);
            font-size: 11px;
            font-weight: 600;
        }

        /* Biography */

        .detail-bio-card {
            margin-bottom: 18px;
        }

        .detail-bio-text {
            margin-top: 17px;
            color: #aaa8b1;
            font-size: 12px;
            line-height: 1.9;
            white-space: pre-line;
        }

        /* ======================================================
           TAPCARD SHARE POSTER
        ======================================================= */

        .share-poster-section {
            margin: 22px 0;
            padding: 27px;
            border: 1px solid rgba(255,255,255,.075);
            border-radius: 15px;
            background: #1d1c22;
            color: #f7f7f5;
        }

        .share-poster-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .share-poster-heading h3 {
            color: #f7f7f5;
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 20px;
            font-weight: 700;
        }

        .share-poster-heading p {
            margin-top: 5px;
            color: #96949e;
            font-size: 11px;
        }

        .share-poster-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border: 1px solid rgba(181,255,88,.2);
            border-radius: 30px;
            background: rgba(181,255,88,.06);
            color: #B5FF58;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .share-poster-status span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #B5FF58;
        }

        .share-poster-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 260px;
            gap: 28px;
            align-items: center;
        }

        .share-poster-preview-wrapper {
            display: flex;
            justify-content: center;
            padding: 25px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 12px;
            background: #111015;
        }

        .tapcard-share-poster {
            position: relative;
            display: flex;
            width: 100%;
            max-width: 380px;
            min-height: 690px;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
            padding: 38px 28px 25px;
            background:
                radial-gradient(circle at 50% 25%, rgba(8,213,219,.075), transparent 40%),
                #f7f7f5;
            color: #151419;
            font-family: 'DM Sans', sans-serif;
            text-align: center;
        }

        .poster-decoration {
            position: absolute;
            z-index: 0;
            border: 1px solid rgba(8,213,219,.18);
            border-radius: 50%;
            pointer-events: none;
        }

        .poster-decoration-one {
            top: -130px;
            right: -100px;
            width: 280px;
            height: 280px;
        }

        .poster-decoration-two {
            bottom: -160px;
            left: -120px;
            width: 310px;
            height: 310px;
            border-color: rgba(255,91,25,.13);
        }

        .poster-brand,
        .poster-main-message,
        .poster-qr-container,
        .poster-person,
        .poster-workspace-details,
        .poster-footer {
            position: relative;
            z-index: 1;
        }

        .poster-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            min-height: 70px;
        }

        .poster-tapcard-symbol {
            display: grid;
            width: 42px;
            height: 42px;
            grid-template-columns: repeat(2, 1fr);
            gap: 4px;
            transform: rotate(-8deg);
        }

        .poster-tapcard-symbol span,
        .poster-footer-symbol span {
            border-radius: 50%;
        }

        .poster-tapcard-symbol span:nth-child(1),
        .poster-footer-symbol span:nth-child(1) {
            background: #08D5DB;
        }

        .poster-tapcard-symbol span:nth-child(2),
        .poster-footer-symbol span:nth-child(2) {
            background: #B5FF58;
        }

        .poster-tapcard-symbol span:nth-child(3),
        .poster-footer-symbol span:nth-child(3) {
            background: #FF5B19;
        }

        .poster-tapcard-symbol span:nth-child(4),
        .poster-footer-symbol span:nth-child(4) {
            background: #FFEB00;
        }

        .poster-tapcard-name {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 3px;
        }

        .poster-workspace-logo {
            max-width: 150px;
            max-height: 55px;
            object-fit: contain;
        }

        .poster-workspace-name {
            color: #777781;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .poster-main-message {
            margin-top: 35px;
        }

        .poster-eyebrow {
            color: #078f94;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 2.5px;
        }

        .poster-main-message h2 {
            margin-top: 12px;
            color: #151419;
            font-family: 'Outfit', sans-serif;
            font-size: 39px;
            font-weight: 800;
            letter-spacing: -1.8px;
            line-height: .99;
        }

        .poster-main-message h2 span {
            color: #079fa4;
        }

        .poster-main-message p {
            max-width: 260px;
            margin: 13px auto 0;
            color: #777781;
            font-size: 11px;
            line-height: 1.6;
        }

        .poster-qr-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 27px;
        }

        .poster-qr-frame {
            display: grid;
            width: 188px;
            height: 188px;
            place-items: center;
            padding: 9px;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 7px 25px rgba(21,20,25,.06);
        }

        .poster-qr-code {
            display: grid;
            width: 100%;
            height: 100%;
            place-items: center;
        }

        .poster-qr-code img,
        .poster-qr-code canvas {
            display: block;
            width: 100% !important;
            height: 100% !important;
            image-rendering: pixelated;
        }

        .poster-qr-caption {
            margin-top: 11px;
            color: #777781;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .poster-person {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 22px;
        }

        .poster-person-name {
            color: #151419;
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 800;
        }

        .poster-person-title,
        .poster-person-company {
            color: #777781;
            font-size: 10px;
            font-weight: 600;
        }

        .poster-workspace-details {
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            margin-top: 25px;
            padding-top: 17px;
            border-top: 1px solid #e6e6e6;
        }

        .poster-connect-heading {
            margin-bottom: 4px;
            color: #151419;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.6px;
        }

        .poster-contact-line {
            max-width: 100%;
            color: #55555e;
            font-size: 9px;
            overflow-wrap: anywhere;
        }

        .poster-contact-line span {
            margin-right: 5px;
            color: #079fa4;
            font-size: 8px;
            font-weight: 800;
        }

        .poster-social-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 4px;
        }

        .poster-social-links span {
            padding: 4px 8px;
            border: 1px solid #e3e3e3;
            border-radius: 20px;
            color: #55555e;
            font-size: 8px;
            font-weight: 700;
        }

        .poster-website {
            margin-top: 5px;
            color: #079fa4;
            font-size: 11px;
            font-weight: 800;
            overflow-wrap: anywhere;
            text-decoration: none;
        }

        .poster-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: auto;
            padding-top: 20px;
            color: #96949e;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.7px;
        }

        .poster-footer-symbol {
            display: grid;
            width: 15px;
            height: 15px;
            grid-template-columns: repeat(2, 1fr);
            gap: 2px;
            transform: rotate(-8deg);
        }

        /* Poster controls */

        .share-poster-controls {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .poster-control-icon {
            display: grid;
            width: 48px;
            height: 48px;
            place-items: center;
            border-radius: 12px;
            background: rgba(8,213,219,.1);
            color: #08D5DB;
        }

        .poster-control-icon svg {
            width: 24px;
            height: 24px;
        }

        .share-poster-controls h4 {
            margin-top: 18px;
            color: #f7f7f5;
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 17px;
            font-weight: 700;
        }

        .share-poster-controls > p {
            margin-top: 8px;
            color: #96949e;
            font-size: 11px;
            line-height: 1.8;
        }

        .poster-information {
            display: flex;
            width: 100%;
            flex-direction: column;
            gap: 15px;
            margin-top: 23px;
            padding: 17px;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 10px;
            background: #151419;
        }

        .poster-information div {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 5px;
        }

        .poster-information span {
            color: #77757f;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .poster-information strong {
            color: #f7f7f5;
            font-size: 10px;
            overflow-wrap: anywhere;
        }

        .poster-url-text {
            color: #08D5DB !important;
        }

        .poster-download-btn,
        .poster-share-btn {
            display: flex;
            width: 100%;
            min-height: 46px;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin-top: 17px;
            padding: 12px 15px;
            border: 1px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 800;
            transition: .2s ease;
        }

        .poster-download-btn svg,
        .poster-share-btn svg {
            width: 17px;
            height: 17px;
        }

        .poster-download-btn {
            background: #08D5DB;
            color: #151419;
        }

        .poster-download-btn:hover {
            transform: translateY(-2px);
            background: #39e5e9;
        }

        .poster-download-btn:disabled {
            opacity: .6;
            cursor: wait;
            transform: none;
        }

        .poster-share-btn {
            margin-top: 9px;
            border-color: rgba(255,255,255,.12);
            background: #292831;
            color: #f7f7f5;
        }

        .poster-share-btn:hover {
            border-color: #08D5DB;
            color: #08D5DB;
        }

        .poster-feedback {
            min-height: 17px;
            margin-top: 10px;
            color: #B5FF58;
            font-size: 10px;
        }

        .poster-tip {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 16px;
            color: #77757f;
            font-size: 9px;
            line-height: 1.7;
        }

        .poster-tip svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            color: #08D5DB;
        }

        /* Actions */

        .detail-actions-card {
            padding: 24px;
            border: 1px solid var(--detail-border);
            border-radius: 12px;
            background: var(--detail-panel);
        }

        .detail-actions-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .detail-actions-heading h3 {
            color: var(--detail-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 20px;
            font-weight: 700;
        }

        .detail-actions-heading p {
            margin-top: 5px;
            color: var(--detail-muted);
            font-size: 11px;
        }

        .detail-actions-heading-icon {
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 11px;
            background: rgba(8,213,219,.08);
            color: var(--detail-cyan);
        }

        .detail-actions-heading-icon svg {
            width: 22px;
            height: 22px;
        }

        .detail-quick-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
            margin-top: 22px;
        }

        .detail-quick-action {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 14px;
            border: 1px solid var(--detail-border);
            border-radius: 9px;
            background: rgba(255,255,255,.015);
            text-decoration: none;
            transition: .2s ease;
        }

        .detail-quick-action:hover {
            border-color: rgba(8,213,219,.3);
            background: var(--detail-panel-hover);
            transform: translateY(-2px);
        }

        .quick-action-icon {
            display: grid;
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 9px;
        }

        .quick-action-icon svg {
            width: 19px;
            height: 19px;
        }

        .quick-action-icon.cyan {
            background: rgba(8,213,219,.1);
            color: var(--detail-cyan);
        }

        .quick-action-icon.lime {
            background: rgba(181,255,88,.1);
            color: var(--detail-lime);
        }

        .quick-action-icon.orange {
            background: rgba(255,91,25,.1);
            color: var(--detail-orange);
        }

        .quick-action-icon.yellow {
            background: rgba(255,235,0,.08);
            color: var(--detail-yellow);
        }

        .detail-quick-action > div:nth-child(2) {
            min-width: 0;
        }

        .detail-quick-action strong {
            display: block;
            color: var(--detail-text);
            font-size: 11px;
            font-weight: 700;
        }

        .detail-quick-action span {
            display: block;
            margin-top: 4px;
            color: var(--detail-muted);
            font-size: 9px;
        }

        .quick-action-arrow {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            margin-left: auto;
            color: #77757f;
            transition: .2s;
        }

        .detail-quick-action:hover .quick-action-arrow {
            color: var(--detail-cyan);
            transform: translateX(3px);
        }

        .detail-actions-divider {
            height: 1px;
            margin: 24px 0;
            background: var(--detail-border);
        }

        .detail-action-group-heading h4 {
            color: var(--detail-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 14px;
            font-weight: 700;
        }

        .detail-action-group-heading p {
            margin-top: 4px;
            color: var(--detail-muted);
            font-size: 10px;
        }

        .detail-action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 16px;
        }

        .detail-action-buttons form {
            display: inline-flex;
        }

        .detail-action-btn {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 13px;
            border: 1px solid transparent;
            border-radius: 7px;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .detail-action-btn svg {
            width: 15px;
            height: 15px;
        }

        .detail-action-btn:hover {
            transform: translateY(-2px);
        }

        .detail-action-btn.success {
            border-color: rgba(181,255,88,.2);
            background: rgba(181,255,88,.1);
            color: var(--detail-lime);
        }

        .detail-action-btn.warning {
            border-color: rgba(255,91,25,.2);
            background: rgba(255,91,25,.1);
            color: #ff9b78;
        }

        .detail-action-btn.cyan {
            border-color: rgba(8,213,219,.2);
            background: rgba(8,213,219,.1);
            color: var(--detail-cyan);
        }

        .detail-action-btn.neutral {
            border-color: var(--detail-border);
            background: rgba(255,255,255,.04);
            color: var(--detail-text);
        }

        .detail-action-btn.primary {
            border-color: rgba(255,235,0,.2);
            background: rgba(255,235,0,.09);
            color: var(--detail-yellow);
        }

        /* Danger zone */

        .detail-danger-zone {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 25px;
            padding-top: 22px;
            border-top: 1px solid rgba(255,91,25,.15);
        }

        .detail-danger-zone > div > span {
            color: var(--detail-orange);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .detail-danger-zone h4 {
            margin-top: 5px;
            color: var(--detail-text);
            font-size: 12px;
            font-weight: 700;
        }

        .detail-danger-zone p {
            margin-top: 4px;
            color: var(--detail-muted);
            font-size: 10px;
        }

        .detail-delete-btn {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 13px;
            border: 1px solid rgba(255,91,25,.2);
            border-radius: 7px;
            background: rgba(255,91,25,.08);
            color: #ff9b78;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
            white-space: nowrap;
        }

        .detail-delete-btn:hover {
            background: rgba(255,91,25,.16);
        }

        .detail-delete-btn svg {
            width: 15px;
            height: 15px;
        }

        /* Responsive */

        @media (max-width: 850px) {

            .card-detail-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .detail-header-actions {
                width: 100%;
            }

            .detail-header-actions a {
                flex: 1;
            }

            .detail-information-grid {
                grid-template-columns: 1fr;
            }

            .share-poster-layout {
                grid-template-columns: 1fr;
            }

            .share-poster-controls {
                max-width: 500px;
                margin: 0 auto;
            }

        }

        @media (max-width: 600px) {

            .card-detail-container {
                padding: 0 15px;
            }

            .detail-cover {
                height: 145px;
            }

            .detail-profile-content {
                padding: 0 17px 19px;
            }

            .detail-profile-top {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .detail-profile-identity {
                align-items: flex-end;
                gap: 11px;
            }

            .detail-avatar {
                width: 70px;
                height: 70px;
                border-radius: 13px;
            }

            .detail-avatar-placeholder {
                font-size: 25px;
            }

            .detail-identity-text h3 {
                font-size: 18px;
            }

            .detail-identity-text p {
                font-size: 10px;
            }

            .detail-profile-tags {
                padding-left: 81px;
            }

            .detail-profile-footer {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .detail-information-card,
            .detail-actions-card {
                padding: 18px;
            }

            .detail-quick-actions {
                grid-template-columns: 1fr;
            }

            .detail-action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .detail-action-buttons form,
            .detail-action-buttons a,
            .detail-action-buttons button {
                width: 100%;
            }

            .detail-action-buttons form button {
                justify-content: center;
            }

            .detail-danger-zone {
                align-items: flex-start;
                flex-direction: column;
            }

            .detail-danger-zone form,
            .detail-delete-btn {
                width: 100%;
            }

            .detail-header-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .share-poster-section {
                padding: 17px;
            }

            .share-poster-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .share-poster-preview-wrapper {
                padding: 10px;
            }

            .tapcard-share-poster {
                min-height: 650px;
                padding: 30px 18px 20px;
            }

            .poster-main-message h2 {
                font-size: 34px;
            }

            .poster-qr-frame {
                width: 170px;
                height: 170px;
            }

        }

    </style>


    {{-- ==========================================================
        QR GENERATION AND POSTER DOWNLOAD
    =========================================================== --}}

    @if($businessCard->status === 'published' && $businessCard->visibility === 'public')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                const poster = document.getElementById('tapcardSharePoster');
                const qrContainer = document.getElementById('tapcardPosterQr');

                const downloadButton = document.getElementById('downloadTapCardPoster');
                const shareButton = document.getElementById('shareTapCardPoster');

                const feedback = document.getElementById('posterDownloadMessage');

                if (!poster || !qrContainer) {
                    return;
                }

                const publicCardUrl = @json($shareUrl);

                /*
                |--------------------------------------------------------------------------
                | Feedback
                |--------------------------------------------------------------------------
                */

                function showPosterMessage(message) {

                    if (feedback) {
                        feedback.textContent = message;
                    }

                }

                /*
                |--------------------------------------------------------------------------
                | Generate QR code
                |--------------------------------------------------------------------------
                */

                if (typeof QRCode === 'undefined') {

                    showPosterMessage(
                        'QR generator could not be loaded. Check your internet connection.'
                    );

                    return;

                }

                new QRCode(qrContainer, {

                    text: publicCardUrl,

                    width: 180,
                    height: 180,

                    colorDark: '#151419',
                    colorLight: '#ffffff',

                    correctLevel: QRCode.CorrectLevel.H

                });


                /*
                |--------------------------------------------------------------------------
                | Wait for poster images
                |--------------------------------------------------------------------------
                */

                async function waitForPosterImages() {

                    const images = Array.from(poster.querySelectorAll('img'));

                    await Promise.all(

                        images.map(function (img) {

                            if (img.complete && img.naturalWidth > 0) {
                                return Promise.resolve();
                            }

                            return new Promise(function (resolve) {

                                img.onload = resolve;
                                img.onerror = resolve;

                            });

                        })

                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Generate poster image
                |--------------------------------------------------------------------------
                */

                async function generatePosterImage() {

                    if (typeof html2canvas === 'undefined') {

                        throw new Error(
                            'The poster image generator could not be loaded.'
                        );

                    }

                    await waitForPosterImages();

                    // Allow the QR code to finish rendering.
                    await new Promise(resolve => setTimeout(resolve, 400));

                    return await html2canvas(poster, {

                        scale: 3,

                        backgroundColor: '#f7f7f5',

                        useCORS: true,

                        allowTaint: false,

                        logging: false,

                        width: poster.offsetWidth,
                        height: poster.offsetHeight,

                        windowWidth: poster.scrollWidth,
                        windowHeight: poster.scrollHeight

                    });

                }


                /*
                |--------------------------------------------------------------------------
                | Download PNG
                |--------------------------------------------------------------------------
                */

                if (downloadButton) {

                    downloadButton.addEventListener('click', async function () {

                        const originalText = downloadButton.innerHTML;

                        try {

                            downloadButton.disabled = true;

                            downloadButton.textContent = 'Preparing poster...';

                            showPosterMessage('Generating your poster...');

                            const canvas = await generatePosterImage();

                            const image = canvas.toDataURL('image/png');

                            const link = document.createElement('a');

                            link.href = image;

                            link.download =
                                'tapcard-' +
                                @json(\Illuminate\Support\Str::slug($fullName)) +
                                '-poster.png';

                            document.body.appendChild(link);

                            link.click();

                            document.body.removeChild(link);

                            showPosterMessage('Your poster has been downloaded successfully.');

                        } catch (error) {

                            console.error('Poster download error:', error);

                            showPosterMessage(
                                'Could not generate poster. Please try again.'
                            );

                        } finally {

                            downloadButton.disabled = false;

                            downloadButton.innerHTML = originalText;

                        }

                    });

                }


                /*
                |--------------------------------------------------------------------------
                | Share poster
                |--------------------------------------------------------------------------
                */

                if (shareButton) {

                    shareButton.addEventListener('click', async function () {

                        try {

                            if (!navigator.share) {

                                showPosterMessage(
                                    'Sharing is not supported on this browser. Download the poster instead.'
                                );

                                return;

                            }

                            showPosterMessage('Preparing poster for sharing...');

                            const canvas = await generatePosterImage();

                            const blob = await new Promise(resolve => {

                                canvas.toBlob(resolve, 'image/png');

                            });

                            if (!blob) {

                                throw new Error('Could not create poster image.');

                            }

                            const file = new File(

                                [blob],

                                'tapcard-poster.png',

                                { type: 'image/png' }

                            );

                            const shareData = {

                                title: @json($shareTitle),

                                text: @json($shareText),

                                url: publicCardUrl

                            };

                            if (
                                navigator.canShare &&
                                navigator.canShare({ files: [file] })
                            ) {

                                shareData.files = [file];

                            }

                            await navigator.share(shareData);

                            showPosterMessage('Poster shared successfully.');

                        } catch (error) {

                            if (error.name !== 'AbortError') {

                                console.error('Poster sharing error:', error);

                                showPosterMessage(
                                    'Unable to share the poster. You can download it instead.'
                                );

                            }

                        }

                    });

                }

            });

        </script>

    @endif

</x-app-layout>
