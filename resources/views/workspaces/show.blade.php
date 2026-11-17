
<x-app-layout>
    <x-slot name="header">
        <div class="workspace-header">
            <div>
                <div class="workspace-breadcrumb">
                    <a href="{{ route('workspaces.index') }}">Workspaces</a>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                    <span>{{ $workspace->name }}</span>
                </div>

                <span class="workspace-eyebrow">WORKSPACE OVERVIEW</span>

                <h2 class="workspace-page-title">
                    {{ $workspace->name }}
                </h2>

                <p class="workspace-page-description">
                    View your organisation's details, workspace activity and settings.
                </p>
            </div>

            <div class="workspace-header-actions">
                <a href="{{ route('workspaces.members.index', $workspace) }}"
                   class="workspace-secondary-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M19 8v6m3-3h-6"/>
                    </svg>
                    Manage Members
                </a>

                @if($workspace->type === 'business')
                    <a href="{{ route('workspaces.edit', $workspace) }}"
                       class="workspace-primary-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/>
                            <path d="m19.4 15 .1.1a1.7 1.7 0 0 1-2.4 2.4l-.1-.1a1.7 1.7 0 0 0-2.9 1.2v.2a1.7 1.7 0 0 1-3.4 0v-.2a1.7 1.7 0 0 0-2.9-1.2l-.1.1a1.7 1.7 0 0 1-2.4-2.4l.1-.1a1.7 1.7 0 0 0-1.2-2.9H4a1.7 1.7 0 0 1 0-3.4h.2a1.7 1.7 0 0 0 1.2-2.9l-.1-.1a1.7 1.7 0 0 1 2.4-2.4l.1.1a1.7 1.7 0 0 0 2.9-1.2V4a1.7 1.7 0 0 1 3.4 0v.2a1.7 1.7 0 0 0 2.9 1.2l.1-.1a1.7 1.7 0 0 1 2.4 2.4l-.1.1a1.7 1.7 0 0 0 1.2 2.9h.2a1.7 1.7 0 0 1 0 3.4h-.2a1.7 1.7 0 0 0-1.2.9Z"/>
                        </svg>
                        Workspace Settings
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="workspace-page">
        <div class="workspace-container">

            @if(session('success'))
                <div class="workspace-alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m5 12 4 4L19 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Workspace identity --}}
            <section class="workspace-hero-card">
                <div class="workspace-hero-decoration"></div>

                <div class="workspace-hero-content">
                    <div class="workspace-identity">
                        <div class="workspace-avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/>
                            </svg>
                        </div>

                        <div class="workspace-identity-info">
                            <div class="workspace-identity-label">
                                <span class="workspace-eyebrow">YOUR WORKSPACE</span>

                                <span class="workspace-status {{ strtolower($workspace->status) }}">
                                    <span></span>
                                    {{ ucfirst($workspace->status) }}
                                </span>
                            </div>

                            <h3>{{ $workspace->name }}</h3>

                            <div class="workspace-location">
                                <span>{{ ucfirst($workspace->type) }} workspace</span>
                                <span class="workspace-dot"></span>
                                <span>{{ $workspace->country ?? 'Country not specified' }}</span>
                            </div>

                            @if($workspace->description)
                                <p class="workspace-hero-description">
                                    {{ $workspace->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="workspace-hero-reference">
                        <span>WORKSPACE ID</span>
                        <strong>{{ $workspace->slug }}</strong>
                    </div>
                </div>
            </section>

            {{-- Statistics --}}
            <section class="workspace-stats">
                <div class="workspace-stat-card">
                    <div class="workspace-stat-top">
                        <span>Business cards</span>
                        <div class="workspace-stat-icon cyan">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="8" cy="12" r="2"/>
                                <path d="M13 10h4M13 14h4"/>
                            </svg>
                        </div>
                    </div>

                    <div class="workspace-stat-value">
                        {{ $workspace->business_cards_count }}
                    </div>

                    <span class="workspace-stat-note">Digital identity cards</span>
                </div>

                <div class="workspace-stat-card">
                    <div class="workspace-stat-top">
                        <span>Workspace members</span>
                        <div class="workspace-stat-icon lime">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M20 8v6m3-3h-6"/>
                            </svg>
                        </div>
                    </div>

                    <div class="workspace-stat-value">
                        {{ $workspace->members_count }}
                    </div>

                    <span class="workspace-stat-note">People in this workspace</span>
                </div>

                <div class="workspace-stat-card">
                    <div class="workspace-stat-top">
                        <span>Workspace type</span>
                        <div class="workspace-stat-icon orange">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 21h18M5 21V7l7-4 7 4v14"/>
                                <path d="M9 9h.01M9 12h.01M9 15h.01M15 9h.01M15 12h.01M15 15h.01"/>
                            </svg>
                        </div>
                    </div>

                    <div class="workspace-stat-value workspace-stat-type">
                        {{ ucfirst($workspace->type) }}
                    </div>

                    <span class="workspace-stat-note">Workspace classification</span>
                </div>
            </section>

            {{-- Organisation information --}}
            <section class="workspace-information-card">
                <div class="workspace-section-heading">
                    <div>
                        <span class="workspace-eyebrow">WORKSPACE PROFILE</span>
                        <h3>Organisation information</h3>
                        <p>Contact details and configuration associated with this workspace.</p>
                    </div>

                    @if($workspace->type === 'business')
                        <a href="{{ route('workspaces.edit', $workspace) }}" class="workspace-edit-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 15l-.8 5Z"/>
                            </svg>
                            Edit details
                        </a>
                    @endif
                </div>

                <div class="workspace-information-grid">
                    <div class="workspace-information-item">
                        <div class="workspace-information-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="m3 7 9 6 9-6"/>
                            </svg>
                        </div>
                        <div>
                            <span>Email address</span>
                            <strong>{{ $workspace->email ?: 'Not provided' }}</strong>
                        </div>
                    </div>

                    <div class="workspace-information-item">
                        <div class="workspace-information-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L9 10.7a16 16 0 0 0 4.3 4.3l1.3-1.3a2 2 0 0 1 2.1-.5c.8.3 1.7.6 2.6.7a2 2 0 0 1 1.7 2Z"/>
                            </svg>
                        </div>
                        <div>
                            <span>Phone number</span>
                            <strong>{{ $workspace->phone ?: 'Not provided' }}</strong>
                        </div>
                    </div>

                    <div class="workspace-information-item">
                        <div class="workspace-information-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/>
                            </svg>
                        </div>
                        <div>
                            <span>Website</span>
                            <strong>
                                @if($workspace->website)
                                    <a href="{{ $workspace->website }}"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        {{ $workspace->website }}
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M14 3h7v7m-11 4L21 3M19 13v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h7"/>
                                        </svg>
                                    </a>
                                @else
                                    Not provided
                                @endif
                            </strong>
                        </div>
                    </div>

                    <div class="workspace-information-item">
                        <div class="workspace-information-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <div>
                            <span>Timezone</span>
                            <strong>{{ $workspace->timezone ?: 'Not specified' }}</strong>
                        </div>
                    </div>

                    <div class="workspace-information-item">
                        <div class="workspace-information-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M8 4h12v16H4V8l4-4Z"/>
                                <path d="M8 4v4H4m4 4h8m-8 4h8"/>
                            </svg>
                        </div>
                        <div>
                            <span>Workspace slug</span>
                            <strong>{{ $workspace->slug }}</strong>
                        </div>
                    </div>

                    <div class="workspace-information-item">
                        <div class="workspace-information-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="4" width="18" height="17" rx="2"/>
                                <path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                        </div>
                        <div>
                            <span>Date created</span>
                            <strong>{{ $workspace->created_at->format('d M Y') }}</strong>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <style>
        :root {
            --workspace-bg: #151419;
            --workspace-panel: #1d1c22;
            --workspace-panel-hover: #24232a;
            --workspace-border: rgba(255,255,255,.075);
            --workspace-text: #f7f7f5;
            --workspace-muted: #96949e;
            --workspace-cyan: #08D5DB;
            --workspace-lime: #B5FF58;
            --workspace-orange: #FF5B19;
        }

        .workspace-page,
        .workspace-header {
            font-family: var(--font-body, 'DM Sans', sans-serif);
        }

        .workspace-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            width: 100%;
        }

        .workspace-breadcrumb {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 17px;
            color: var(--workspace-muted);
            font-size: 11px;
        }

        .workspace-breadcrumb a {
            color: var(--workspace-muted);
            text-decoration: none;
            transition: .2s;
        }

        .workspace-breadcrumb a:hover {
            color: var(--workspace-cyan);
        }

        .workspace-breadcrumb svg {
            width: 13px;
            height: 13px;
        }

        .workspace-eyebrow {
            display: block;
            margin-bottom: 7px;
            color: var(--workspace-cyan);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .workspace-page-title {
            color: var(--workspace-text) !important;
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: clamp(24px, 3vw, 30px);
            font-weight: 700;
            letter-spacing: -1px;
        }

        .workspace-page-description {
            margin-top: 5px;
            color: var(--workspace-muted);
            font-size: 12px;
        }

        .workspace-header-actions {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
        }

        .workspace-primary-btn,
        .workspace-secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none;
            transition: .25s ease;
            white-space: nowrap;
        }

        .workspace-primary-btn svg,
        .workspace-secondary-btn svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .workspace-primary-btn {
            border: 1px solid var(--workspace-cyan);
            background: var(--workspace-cyan);
            color: #151419 !important;
        }

        .workspace-primary-btn:hover {
            background: #39e5e9;
            transform: translateY(-2px);
        }

        .workspace-secondary-btn {
            border: 1px solid var(--workspace-border);
            background: var(--workspace-panel);
            color: var(--workspace-text);
        }

        .workspace-secondary-btn:hover {
            border-color: var(--workspace-cyan);
            color: var(--workspace-cyan);
        }

        .workspace-page {
            min-height: calc(100vh - 100px);
            padding: 32px 0 65px;
            background: var(--workspace-bg);
        }

        .workspace-container {
            width: 100%;
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 28px;
        }

        .workspace-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            padding: 14px 17px;
            border: 1px solid rgba(181,255,88,.2);
            border-radius: 10px;
            background: rgba(181,255,88,.07);
            color: var(--workspace-lime);
            font-size: 12px;
        }

        .workspace-alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Hero identity card */

        .workspace-hero-card {
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            padding: 29px;
            border: 1px solid rgba(8,213,219,.16);
            border-radius: 15px;
            background:
                radial-gradient(ellipse at 90% 0%, rgba(8,213,219,.11), transparent 40%),
                var(--workspace-panel);
        }

        .workspace-hero-decoration {
            position: absolute;
            top: -100px;
            right: -60px;
            width: 260px;
            height: 260px;
            border: 1px solid rgba(8,213,219,.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .workspace-hero-decoration::before,
        .workspace-hero-decoration::after {
            position: absolute;
            inset: 25px;
            border: 1px solid rgba(8,213,219,.06);
            border-radius: 50%;
            content: "";
        }

        .workspace-hero-decoration::after {
            inset: 55px;
        }

        .workspace-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .workspace-identity {
            display: flex;
            align-items: center;
            gap: 19px;
            min-width: 0;
        }

        .workspace-avatar {
            display: grid;
            width: 68px;
            height: 68px;
            flex-shrink: 0;
            place-items: center;
            border: 1px solid rgba(8,213,219,.2);
            border-radius: 17px;
            background: rgba(8,213,219,.1);
            color: var(--workspace-cyan);
        }

        .workspace-avatar svg {
            width: 32px;
            height: 32px;
            stroke-width: 1.5;
        }

        .workspace-identity-label {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .workspace-identity-label .workspace-eyebrow {
            margin-bottom: 0;
        }

        .workspace-identity-info h3 {
            margin-top: 9px;
            color: var(--workspace-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -.7px;
            overflow-wrap: anywhere;
        }

        .workspace-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 9px;
            border: 1px solid rgba(181,255,88,.16);
            border-radius: 30px;
            background: rgba(181,255,88,.08);
            color: var(--workspace-lime);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .workspace-status span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .workspace-status.inactive,
        .workspace-status.suspended {
            border-color: rgba(255,91,25,.2);
            background: rgba(255,91,25,.08);
            color: var(--workspace-orange);
        }

        .workspace-location {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 8px;
            color: var(--workspace-muted);
            font-size: 11px;
        }

        .workspace-dot {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #77757f;
        }

        .workspace-hero-description {
            max-width: 600px;
            margin-top: 11px;
            color: #85838d;
            font-size: 12px;
            line-height: 1.7;
        }

        .workspace-hero-reference {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 7px;
            flex-shrink: 0;
        }

        .workspace-hero-reference span {
            color: #77757f;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.4px;
        }

        .workspace-hero-reference strong {
            color: var(--workspace-cyan);
            font-size: 12px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        /* Statistics */

        .workspace-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 35px;
        }

        .workspace-stat-card {
            min-height: 145px;
            padding: 20px;
            border: 1px solid var(--workspace-border);
            border-radius: 12px;
            background: var(--workspace-panel);
            transition: .25s ease;
        }

        .workspace-stat-card:hover {
            transform: translateY(-3px);
            border-color: rgba(8,213,219,.25);
        }

        .workspace-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--workspace-muted);
            font-size: 11px;
            font-weight: 600;
        }

        .workspace-stat-icon {
            display: grid;
            width: 35px;
            height: 35px;
            place-items: center;
            border-radius: 9px;
        }

        .workspace-stat-icon svg {
            width: 18px;
            height: 18px;
            stroke-width: 1.7;
        }

        .workspace-stat-icon.cyan {
            background: rgba(8,213,219,.1);
            color: var(--workspace-cyan);
        }

        .workspace-stat-icon.lime {
            background: rgba(181,255,88,.1);
            color: var(--workspace-lime);
        }

        .workspace-stat-icon.orange {
            background: rgba(255,91,25,.1);
            color: var(--workspace-orange);
        }

        .workspace-stat-value {
            margin-top: 13px;
            color: var(--workspace-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .workspace-stat-type {
            font-size: 23px;
        }

        .workspace-stat-note {
            display: block;
            margin-top: 9px;
            color: #77757f;
            font-size: 10px;
        }

        /* Organisation details */

        .workspace-information-card {
            padding: 25px;
            border: 1px solid var(--workspace-border);
            border-radius: 13px;
            background: var(--workspace-panel);
        }

        .workspace-section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding-bottom: 21px;
            border-bottom: 1px solid var(--workspace-border);
        }

        .workspace-section-heading .workspace-eyebrow {
            margin-bottom: 6px;
        }

        .workspace-section-heading h3 {
            color: var(--workspace-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 19px;
            font-weight: 700;
        }

        .workspace-section-heading p {
            margin-top: 5px;
            color: var(--workspace-muted);
            font-size: 11px;
        }

        .workspace-edit-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--workspace-cyan);
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .workspace-edit-link:hover {
            color: #5ce9ed;
        }

        .workspace-edit-link svg {
            width: 15px;
            height: 15px;
        }

        .workspace-information-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            column-gap: 35px;
            row-gap: 0;
        }

        .workspace-information-item {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,.045);
        }

        .workspace-information-icon {
            display: grid;
            width: 37px;
            height: 37px;
            flex-shrink: 0;
            place-items: center;
            border: 1px solid rgba(8,213,219,.1);
            border-radius: 9px;
            background: rgba(8,213,219,.06);
            color: var(--workspace-cyan);
        }

        .workspace-information-icon svg {
            width: 17px;
            height: 17px;
            stroke-width: 1.7;
        }

        .workspace-information-item > div:last-child {
            min-width: 0;
        }

        .workspace-information-item span {
            display: block;
            margin-bottom: 5px;
            color: #77757f;
            font-size: 10px;
        }

        .workspace-information-item strong {
            display: block;
            overflow-wrap: anywhere;
            color: var(--workspace-text);
            font-size: 12px;
            font-weight: 600;
        }

        .workspace-information-item strong a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--workspace-cyan);
            text-decoration: none;
        }

        .workspace-information-item strong a:hover {
            text-decoration: underline;
        }

        .workspace-information-item strong a svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }

        @media (max-width: 900px) {
            .workspace-hero-content {
                align-items: flex-start;
                flex-direction: column;
            }

            .workspace-hero-reference {
                align-items: flex-start;
                padding-left: 87px;
            }
        }

        @media (max-width: 768px) {
            .workspace-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .workspace-header-actions {
                width: 100%;
            }

            .workspace-header-actions a {
                flex: 1;
            }

            .workspace-container {
                padding: 0 17px;
            }

            .workspace-stats {
                gap: 10px;
            }

            .workspace-stat-card {
                padding: 15px;
            }

            .workspace-information-card {
                padding: 19px;
            }
        }

        @media (max-width: 540px) {
            .workspace-stats {
                grid-template-columns: 1fr 1fr;
            }

            .workspace-stat-card:last-child {
                grid-column: span 2;
            }

            .workspace-information-grid {
                grid-template-columns: 1fr;
            }

            .workspace-hero-card {
                padding: 20px;
            }

            .workspace-identity {
                align-items: flex-start;
                gap: 13px;
            }

            .workspace-avatar {
                width: 49px;
                height: 49px;
                border-radius: 12px;
            }

            .workspace-avatar svg {
                width: 24px;
                height: 24px;
            }

            .workspace-identity-info h3 {
                font-size: 19px;
            }

            .workspace-hero-reference {
                padding-left: 62px;
            }

            .workspace-header-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .workspace-header-actions a {
                width: 100%;
            }

            .workspace-section-heading {
                align-items: flex-start;
            }
        }
    </style>
</x-app-layout>
