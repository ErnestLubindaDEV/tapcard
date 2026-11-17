<x-app-layout>
    <x-slot name="header">
        <div class="workspace-header">
            <div>
                <span class="workspace-eyebrow">WORKSPACE MANAGEMENT</span>
                <h2 class="workspace-page-title">My Workspaces</h2>
                <p class="workspace-page-description">
                    Manage your personal and business workspaces from one place.
                </p>
            </div>

            <a href="{{ route('workspaces.create') }}" class="workspace-primary-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" />
                </svg>
                Create Business Workspace
            </a>
        </div>
    </x-slot>

    <div class="workspace-page">
        <div class="workspace-container">

            @if(session('success'))
            <div class="workspace-alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="m5 12 4 4L19 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Workspace statistics --}}
            <div class="workspace-stats">
                <div class="workspace-stat-card">
                    <div class="workspace-stat-top">
                        <span>Total workspaces</span>
                        <div class="workspace-stat-icon cyan">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="3" width="8" height="8" rx="2" />
                                <rect x="13" y="3" width="8" height="8" rx="2" />
                                <rect x="3" y="13" width="8" height="8" rx="2" />
                                <rect x="13" y="13" width="8" height="8" rx="2" />
                            </svg>
                        </div>
                    </div>
                    <div class="workspace-stat-value">{{ $workspaces->count() }}</div>
                    <span class="workspace-stat-note">All your workspaces</span>
                </div>

                <div class="workspace-stat-card">
                    <div class="workspace-stat-top">
                        <span>Personal workspaces</span>
                        <div class="workspace-stat-icon lime">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                    </div>
                    <div class="workspace-stat-value">
                        {{ $workspaces->where('type', 'personal')->count() }}
                    </div>
                    <span class="workspace-stat-note">Your individual spaces</span>
                </div>

                <div class="workspace-stat-card">
                    <div class="workspace-stat-top">
                        <span>Business workspaces</span>
                        <div class="workspace-stat-icon orange">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 12h.01M9 15h.01M15 9h.01M15 12h.01M15 15h.01" />
                            </svg>
                        </div>
                    </div>
                    <div class="workspace-stat-value">
                        {{ $workspaces->where('type', 'business')->count() }}
                    </div>
                    <span class="workspace-stat-note">Your organisation spaces</span>
                </div>
            </div>

            {{-- Workspace list heading --}}
            <div class="workspace-section-heading">
                <div>
                    <span class="workspace-eyebrow">YOUR ORGANISATION</span>
                    <h3>Your workspaces</h3>
                </div>
                <span class="workspace-count">
                    {{ $workspaces->count() }} {{ Str::plural('workspace', $workspaces->count()) }}
                </span>
            </div>

            {{-- Workspace cards --}}
            <div class="workspace-list">
                @forelse($workspaces as $workspace)
                <div class="workspace-card">

                    <div class="workspace-card-main">
                        <div class="workspace-avatar {{ $workspace->type === 'business' ? 'business' : 'personal' }}">
                            @if($workspace->type === 'business')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 12h.01M9 15h.01M15 9h.01M15 12h.01M15 15h.01" />
                            </svg>
                            @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            @endif
                        </div>

                        <div class="workspace-information">
                            <div class="workspace-name-row">
                                <h4>{{ $workspace->name }}</h4>

                                <span class="workspace-type {{ $workspace->type }}">
                                    {{ $workspace->type }}
                                </span>
                            </div>

                            <div class="workspace-meta">
                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M20 8v6m3-3h-6" />
                                    </svg>
                                    {{ $workspace->active_members_count }} members
                                </span>

                                <span class="workspace-meta-divider"></span>

                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                        <path d="M8 8h8M8 12h8M8 16h5" />
                                    </svg>
                                    {{ $workspace->business_cards_count }} cards
                                </span>
                            </div>

                            @if($workspace->description)
                            <p class="workspace-description">
                                {{ $workspace->description }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <div class="workspace-card-actions">
                        <a href="{{ route('workspaces.show', $workspace) }}" class="workspace-secondary-btn">
                            Open workspace
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M5 12h14m-7-7 7 7-7 7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        @if($workspace->type === 'business')
                        <a href="{{ route('workspaces.edit', $workspace) }}" class="workspace-manage-btn">
                            Manage
                        </a>
                        @endif
                    </div>

                </div>
                @empty
                <div class="workspace-empty-state">
                    <div class="workspace-empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="3" width="18" height="18" rx="4" />
                            <path d="M12 8v8m-4-4h8" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </div>

                    <span class="workspace-eyebrow">GET STARTED</span>
                    <h3>No workspaces yet</h3>
                    <p>
                        Create a business workspace to start managing your organisation's
                        digital business cards and team.
                    </p>

                    <a href="{{ route('workspaces.create') }}" class="workspace-primary-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        Create your first workspace
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Workspace Quick Actions --}}
            <section class="workspace-quick-actions">

                <div class="workspace-section-heading">
                    <div>
                        <span class="workspace-eyebrow">WORKSPACE MANAGEMENT</span>
                        <h3>Quick actions</h3>
                        <p>Manage your digital identity and workspace resources.</p>
                    </div>
                </div>

                <div class="workspace-action-grid">

                    <a href="{{ route('business-cards.index', $workspace) }}"
                        class="workspace-action-card">

                        <div class="workspace-action-icon cyan">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <circle cx="8" cy="12" r="2" />
                                <path d="M13 10h4M13 14h4" />
                            </svg>
                        </div>

                        <div>
                            <h4>Business Cards</h4>
                            <p>View and manage digital business cards.</p>
                        </div>

                        <svg class="workspace-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('business-cards.create', $workspace) }}"
                        class="workspace-action-card">

                        <div class="workspace-action-icon lime">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>

                        <div>
                            <h4>Create Business Card</h4>
                            <p>Create a new digital identity card.</p>
                        </div>

                        <svg class="workspace-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('workspaces.members.index', $workspace) }}"
                        class="workspace-action-card">

                        <div class="workspace-action-icon orange">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M20 8v6m3-3h-6" />
                            </svg>
                        </div>

                        <div>
                            <h4>Manage Members</h4>
                            <p>Manage your workspace team and roles.</p>
                        </div>

                        <svg class="workspace-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>

                    @if($workspace->type === 'business')
                    <a href="{{ route('workspaces.edit', $workspace) }}"
                        class="workspace-action-card">

                        <div class="workspace-action-icon yellow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="3" />
                                <path d="m19.4 15 .1.1a1.7 1.7 0 0 1-2.4 2.4l-.1-.1a1.7 1.7 0 0 0-2.9 1.2v.2a1.7 1.7 0 0 1-3.4 0v-.2a1.7 1.7 0 0 0-2.9-1.2l-.1.1a1.7 1.7 0 0 1-2.4-2.4l.1-.1a1.7 1.7 0 0 0-1.2-2.9H4a1.7 1.7 0 0 1 0-3.4h.2a1.7 1.7 0 0 0 1.2-2.9l-.1-.1a1.7 1.7 0 0 1 2.4-2.4l.1.1a1.7 1.7 0 0 0 2.9-1.2V4a1.7 1.7 0 0 1 3.4 0v.2a1.7 1.7 0 0 0 2.9 1.2l.1-.1a1.7 1.7 0 0 1 2.4 2.4l-.1.1a1.7 1.7 0 0 0 1.2 2.9h.2a1.7 1.7 0 0 1 0 3.4h-.2a1.7 1.7 0 0 0-1.2.9Z" />
                            </svg>
                        </div>

                        <div>
                            <h4>Workspace Settings</h4>
                            <p>Update organisation information and preferences.</p>
                        </div>

                        <svg class="workspace-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>
                    @endif

                </div>
            </section>

        </div>
    </div>

    <style>
        :root {
            --workspace-bg: #151419;
            --workspace-panel: #1d1c22;
            --workspace-panel-hover: #24232a;
            --workspace-border: rgba(255, 255, 255, .075);
            --workspace-text: #f7f7f5;
            --workspace-muted: #96949e;
            --workspace-cyan: #08D5DB;
            --workspace-lime: #B5FF58;
            --workspace-orange: #FF5B19;
            --workspace-yellow: #FFEB00;
        }

        .workspace-page,
        .workspace-header {
            font-family: var(--font-body, 'DM Sans', sans-serif);
        }

        .workspace-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            width: 100%;
        }

        .workspace-eyebrow {
            display: block;
            margin-bottom: 8px;
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
            font-size: 13px;
        }

        .workspace-primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 44px;
            padding: 0 18px;
            border: 1px solid var(--workspace-cyan);
            border-radius: 9px;
            background: var(--workspace-cyan);
            color: #151419 !important;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            transition: .25s ease;
            white-space: nowrap;
        }

        .workspace-primary-btn:hover {
            transform: translateY(-2px);
            background: #39e5e9;
            box-shadow: 0 8px 25px rgba(8, 213, 219, .16);
        }

        .workspace-primary-btn svg,
        .workspace-secondary-btn svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .workspace-page {
            min-height: calc(100vh - 100px);
            padding: 34px 0 65px;
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
            margin-bottom: 24px;
            padding: 14px 17px;
            border: 1px solid rgba(181, 255, 88, .2);
            border-radius: 10px;
            background: rgba(181, 255, 88, .07);
            color: var(--workspace-lime);
            font-size: 13px;
        }

        .workspace-alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .workspace-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 45px;
        }

        .workspace-stat-card {
            min-height: 145px;
            padding: 21px;
            border: 1px solid var(--workspace-border);
            border-radius: 13px;
            background: var(--workspace-panel);
            transition: .25s ease;
        }

        .workspace-stat-card:hover {
            transform: translateY(-3px);
            border-color: rgba(8, 213, 219, .25);
        }

        .workspace-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--workspace-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .workspace-stat-icon {
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 10px;
        }

        .workspace-stat-icon svg {
            width: 19px;
            height: 19px;
            stroke-width: 1.7;
        }

        .workspace-stat-icon.cyan {
            background: rgba(8, 213, 219, .1);
            color: var(--workspace-cyan);
        }

        .workspace-stat-icon.lime {
            background: rgba(181, 255, 88, .1);
            color: var(--workspace-lime);
        }

        .workspace-stat-icon.orange {
            background: rgba(255, 91, 25, .1);
            color: var(--workspace-orange);
        }

        .workspace-stat-value {
            margin-top: 12px;
            color: var(--workspace-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .workspace-stat-note {
            display: block;
            margin-top: 9px;
            color: #77757f;
            font-size: 11px;
        }

        .workspace-section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 17px;
        }

        .workspace-section-heading .workspace-eyebrow {
            margin-bottom: 5px;
        }

        .workspace-section-heading h3 {
            color: var(--workspace-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -.5px;
        }

        .workspace-count {
            padding: 6px 11px;
            border: 1px solid var(--workspace-border);
            border-radius: 30px;
            color: var(--workspace-muted);
            font-size: 11px;
        }

        .workspace-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .workspace-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 21px;
            border: 1px solid var(--workspace-border);
            border-radius: 13px;
            background: var(--workspace-panel);
            transition: .25s ease;
        }

        .workspace-card:hover {
            border-color: rgba(8, 213, 219, .25);
            background: var(--workspace-panel-hover);
            transform: translateY(-2px);
        }

        .workspace-card-main {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .workspace-avatar {
            display: grid;
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            place-items: center;
            border: 1px solid transparent;
            border-radius: 13px;
        }

        .workspace-avatar svg {
            width: 24px;
            height: 24px;
            stroke-width: 1.6;
        }

        .workspace-avatar.business {
            border-color: rgba(8, 213, 219, .15);
            background: rgba(8, 213, 219, .09);
            color: var(--workspace-cyan);
        }

        .workspace-avatar.personal {
            border-color: rgba(181, 255, 88, .15);
            background: rgba(181, 255, 88, .08);
            color: var(--workspace-lime);
        }

        .workspace-information {
            min-width: 0;
        }

        .workspace-name-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .workspace-name-row h4 {
            overflow-wrap: anywhere;
            color: var(--workspace-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 16px;
            font-weight: 650;
        }

        .workspace-type {
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .7px;
            text-transform: uppercase;
        }

        .workspace-type.business {
            background: rgba(8, 213, 219, .1);
            color: var(--workspace-cyan);
        }

        .workspace-type.personal {
            background: rgba(181, 255, 88, .1);
            color: var(--workspace-lime);
        }

        .workspace-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 9px;
            color: var(--workspace-muted);
            font-size: 11px;
        }

        .workspace-meta span:not(.workspace-meta-divider) {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .workspace-meta svg {
            width: 14px;
            height: 14px;
            stroke-width: 1.7;
        }

        .workspace-meta-divider {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #68666f;
        }

        .workspace-description {
            margin-top: 8px;
            color: #85838d;
            font-size: 12px;
            line-height: 1.6;
        }

        .workspace-card-actions {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            gap: 9px;
        }

        .workspace-secondary-btn,
        .workspace-manage-btn {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 13px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
            white-space: nowrap;
        }

        .workspace-secondary-btn {
            border: 1px solid rgba(255, 255, 255, .12);
            background: transparent;
            color: var(--workspace-text);
        }

        .workspace-secondary-btn:hover {
            border-color: var(--workspace-cyan);
            color: var(--workspace-cyan);
        }

        .workspace-manage-btn {
            border: 1px solid var(--workspace-cyan);
            background: var(--workspace-cyan);
            color: #151419;
        }

        .workspace-manage-btn:hover {
            background: #39e5e9;
        }

        .workspace-empty-state {
            display: flex;
            min-height: 330px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 45px 20px;
            border: 1px dashed rgba(255, 255, 255, .15);
            border-radius: 15px;
            background: var(--workspace-panel);
            text-align: center;
        }

        .workspace-empty-icon {
            display: grid;
            width: 65px;
            height: 65px;
            margin-bottom: 22px;
            place-items: center;
            border: 1px solid rgba(8, 213, 219, .2);
            border-radius: 18px;
            background: rgba(8, 213, 219, .08);
            color: var(--workspace-cyan);
        }

        .workspace-empty-icon svg {
            width: 29px;
            height: 29px;
        }

        .workspace-empty-state .workspace-eyebrow {
            margin-bottom: 8px;
        }

        .workspace-empty-state h3 {
            color: var(--workspace-text);
            font-family: var(--font-display, 'Outfit', sans-serif);
            font-size: 22px;
            font-weight: 700;
        }

        .workspace-empty-state p {
            max-width: 410px;
            margin: 9px 0 22px;
            color: var(--workspace-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            .workspace-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .workspace-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 35px;
            }

            .workspace-stat-card:first-child {
                grid-column: span 2;
            }

            .workspace-container {
                padding: 0 17px;
            }

            .workspace-card {
                align-items: flex-start;
                flex-direction: column;
                gap: 18px;
                padding: 17px;
            }

            .workspace-card-actions {
                width: 100%;
            }

            .workspace-card-actions a {
                flex: 1;
            }
        }

        @media (max-width: 480px) {
            .workspace-stats {
                grid-template-columns: 1fr 1fr;
            }

            .workspace-stat-card {
                min-height: 130px;
                padding: 15px;
            }

            .workspace-stat-value {
                font-size: 27px;
            }

            .workspace-section-heading h3 {
                font-size: 18px;
            }

            .workspace-primary-btn {
                width: 100%;
            }

            .workspace-meta {
                gap: 7px;
            }

            .workspace-card-main {
                align-items: flex-start;
                gap: 12px;
            }

            .workspace-avatar {
                width: 43px;
                height: 43px;
            }

            .workspace-card-actions {
                flex-wrap: wrap;
            }
        }


        .workspace-quick-actions {
            margin-bottom: 30px;
        }

        .workspace-action-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
            margin-top: 17px;
        }

        .workspace-action-card {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
            padding: 19px;
            border: 1px solid var(--workspace-border);
            border-radius: 12px;
            background: var(--workspace-panel);
            text-decoration: none;
            transition: .25s ease;
        }

        .workspace-action-card:hover {
            transform: translateY(-3px);
            border-color: rgba(8, 213, 219, .3);
            background: var(--workspace-panel-hover);
        }

        .workspace-action-icon {
            display: grid;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 11px;
        }

        .workspace-action-icon svg {
            width: 21px;
            height: 21px;
        }

        .workspace-action-icon.cyan {
            color: var(--workspace-cyan);
            background: rgba(8, 213, 219, .1);
        }

        .workspace-action-icon.lime {
            color: var(--workspace-lime);
            background: rgba(181, 255, 88, .1);
        }

        .workspace-action-icon.orange {
            color: var(--workspace-orange);
            background: rgba(255, 91, 25, .1);
        }

        .workspace-action-icon.yellow {
            color: #FFEB00;
            background: rgba(255, 235, 0, .08);
        }

        .workspace-action-card h4 {
            color: var(--workspace-text);
            font-size: 13px;
            font-weight: 700;
        }

        .workspace-action-card p {
            margin-top: 4px;
            color: var(--workspace-muted);
            font-size: 10px;
            line-height: 1.5;
        }

        .workspace-action-arrow {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
            margin-left: auto;
            color: #77757f;
            transition: .2s;
        }

        .workspace-action-card:hover .workspace-action-arrow {
            color: var(--workspace-cyan);
            transform: translateX(3px);
        }

        @media (max-width: 600px) {
            .workspace-action-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-layout>