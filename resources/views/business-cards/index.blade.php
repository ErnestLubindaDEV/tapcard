
<x-app-layout>
    <x-slot name="header">
        <div class="tc-header">
            <div class="tc-header-content">
                <nav class="tc-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('workspaces.index') }}">Workspaces</a>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                    <a href="{{ route('workspaces.show', $workspace) }}">
                        {{ $workspace->name }}
                    </a>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                    <span>Business Cards</span>
                </nav>

                <span class="tc-eyebrow">DIGITAL IDENTITY MANAGEMENT</span>
                <h1 class="tc-header-title">Business Cards</h1>
                <p class="tc-header-description">
                    Manage and share the digital identities in {{ $workspace->name }}.
                </p>
            </div>

            <a href="{{ route('business-cards.create', ['workspace' => $workspace->id]) }}"
               class="tc-button tc-button-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Create new card
            </a>
        </div>
    </x-slot>

    <div class="tc-page">
        <div class="tc-container">

            {{-- Page introduction --}}
            <section class="tc-intro">
                <div>
                    <span class="tc-eyebrow">YOUR DIGITAL IDENTITIES</span>
                    <h2>Your card collection</h2>
                    <p>
                        Create, customise and manage your professional identity in one place.
                    </p>
                </div>

                <div class="tc-workspace-chip">
                    <span class="tc-workspace-indicator"></span>
                    <span>{{ $workspace->name }}</span>
                </div>
            </section>

            {{-- Notifications --}}
            @if(session('success'))
                <div class="tc-alert tc-alert-success" role="status">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m5 12 4 4L19 6"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="tc-alert tc-alert-error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m9 9 6 6m0-6-6 6"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="tc-alert tc-alert-error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4m0 4h.01"/>
                    </svg>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Statistics --}}
            <section class="tc-stats">
                <article class="tc-stat-card">
                    <div class="tc-stat-heading">
                        <span>Total cards</span>
                        <div class="tc-stat-icon cyan">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="8" cy="12" r="2"/>
                                <path d="M13 10h4M13 14h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="tc-stat-value">{{ $cards->total() }}</div>
                    <span class="tc-stat-caption">Cards in this workspace</span>
                </article>

                <article class="tc-stat-card">
                    <div class="tc-stat-heading">
                        <span>Published on this page</span>
                        <div class="tc-stat-icon lime">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="m8 12 3 3 5-6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="tc-stat-value lime-text">
                        {{ $cards->getCollection()->where('status', 'published')->count() }}
                    </div>
                    <span class="tc-stat-caption">Live digital identities</span>
                </article>

                <article class="tc-stat-card">
                    <div class="tc-stat-heading">
                        <span>Drafts on this page</span>
                        <div class="tc-stat-icon orange">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                <path d="M14 2v6h6M8 13h8M8 17h5"/>
                            </svg>
                        </div>
                    </div>
                    <div class="tc-stat-value orange-text">
                        {{ $cards->getCollection()->where('status', 'draft')->count() }}
                    </div>
                    <span class="tc-stat-caption">Awaiting publication</span>
                </article>
            </section>

            {{-- Card collection heading --}}
            <section class="tc-collection">
                <div class="tc-collection-heading">
                    <div>
                        <span class="tc-eyebrow">CARD COLLECTION</span>
                        <h2>Your digital cards</h2>
                        <p>Manage your profiles and their public presence.</p>
                    </div>

                    <span class="tc-count">
                        {{ $cards->total() }}
                        {{ Str::plural('card', $cards->total()) }}
                    </span>
                </div>

                @if($cards->count())
                    <div class="tc-card-grid">
                        @foreach($cards as $card)
                            <article class="tc-business-card">

                                {{-- Cover --}}
                                <div class="tc-card-cover">
                                    @if($card->cover_photo)
                                        <img
                                            src="{{ \App\Support\StorageUrl::url($card->cover_photo) }}"
                                            alt="Cover image for {{ $card->full_name }}"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="tc-cover-placeholder">
                                            <div class="tc-cover-orbit"></div>
                                            <div class="tc-cover-orbit tc-cover-orbit-two"></div>
                                            <span>TAPCARD</span>
                                        </div>
                                    @endif

                                    <div class="tc-card-badges">
                                        @if($card->is_primary)
                                            <span class="tc-badge tc-badge-primary">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="m12 2 3 6 7 .9-5 4.8 1.2 7L12 17.4 5.8 21l1.2-7L2 8.9 9 8z"/>
                                                </svg>
                                                Primary
                                            </span>
                                        @endif

                                        <span class="tc-badge {{ $card->status === 'published' ? 'tc-badge-published' : 'tc-badge-draft' }}">
                                            <span class="tc-status-dot"></span>
                                            {{ ucfirst($card->status) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Card information --}}
                                <div class="tc-card-body">
                                    <div class="tc-profile-row">
                                        @if($card->profile_photo)
                                            <img
                                                src="{{ \App\Support\StorageUrl::url($card->profile_photo) }}"
                                                class="tc-avatar"
                                                alt="{{ $card->full_name }}"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="tc-avatar tc-avatar-placeholder">
                                                {{ strtoupper(substr($card->first_name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div class="tc-person-details">
                                            <h3>{{ $card->full_name }}</h3>
                                            <p>
                                                {{ $card->job_title ?: 'Professional' }}
                                                @if($card->company_name)
                                                    <span>at</span> {{ $card->company_name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="tc-card-details">
                                        <div class="tc-detail">
                                            <span>DESIGN THEME</span>
                                            <strong>{{ $card->theme?->name ?? 'Default' }}</strong>
                                        </div>

                                        <div class="tc-detail">
                                            <span>VISIBILITY</span>
                                            <strong>{{ ucfirst($card->visibility) }}</strong>
                                        </div>
                                    </div>

                                    <div class="tc-card-actions">
                                        <a href="{{ route('business-cards.show', [
                                            'workspace' => $workspace->id,
                                            'businessCard' => $card->id
                                        ]) }}"
                                           class="tc-action tc-action-secondary">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Manage
                                        </a>

                                        <a href="{{ route('business-cards.edit', [
                                            'workspace' => $workspace->id,
                                            'businessCard' => $card->id
                                        ]) }}"
                                           class="tc-action tc-action-primary">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 15l-.8 5Z"/>
                                            </svg>
                                            Edit card
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($cards->hasPages())
                        <div class="tc-pagination">
                            {{ $cards->links() }}
                        </div>
                    @endif

                @else
                    {{-- Empty state --}}
                    <div class="tc-empty-state">
                        <div class="tc-empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="8" cy="12" r="2"/>
                                <path d="M13 10h4M13 14h4"/>
                            </svg>
                        </div>

                        <span class="tc-eyebrow">YOUR DIGITAL IDENTITY STARTS HERE</span>
                        <h3>No business cards yet</h3>
                        <p>
                            Create your first digital business card and make it easier
                            for people to connect with you. Share your profile, contact
                            details and professional links in one place.
                        </p>

                        <a href="{{ route('business-cards.create', ['workspace' => $workspace->id]) }}"
                           class="tc-button tc-button-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Create your first card
                        </a>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <style>
        :root {
            --tc-bg: #151419;
            --tc-panel: #1d1c22;
            --tc-panel-hover: #24232a;
            --tc-border: rgba(255,255,255,.075);
            --tc-text: #f7f7f5;
            --tc-muted: #96949e;
            --tc-cyan: #08D5DB;
            --tc-lime: #B5FF58;
            --tc-orange: #FF5B19;
            --tc-yellow: #FFEB00;
            --tc-display: var(--font-display, 'Outfit', sans-serif);
            --tc-body: var(--font-body, 'DM Sans', sans-serif);
        }

        .tc-page,
        .tc-header {
            font-family: var(--tc-body);
        }

        .tc-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            width: 100%;
        }

        .tc-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
            color: var(--tc-muted);
            font-size: 11px;
        }

        .tc-breadcrumb a {
            color: var(--tc-muted);
            text-decoration: none;
            transition: color .2s ease;
        }

        .tc-breadcrumb a:hover {
            color: var(--tc-cyan);
        }

        .tc-breadcrumb svg {
            width: 12px;
            height: 12px;
            opacity: .6;
        }

        .tc-breadcrumb span {
            color: var(--tc-text);
        }

        .tc-eyebrow {
            display: block;
            margin-bottom: 8px;
            color: var(--tc-cyan);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.8px;
        }

        .tc-header-title {
            color: var(--tc-text) !important;
            font-family: var(--tc-display);
            font-size: clamp(25px, 3vw, 32px);
            font-weight: 700;
            letter-spacing: -1.2px;
            line-height: 1.15;
        }

        .tc-header-description {
            margin-top: 7px;
            color: var(--tc-muted);
            font-size: 12px;
        }

        .tc-button {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 18px;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none;
            transition: .25s ease;
            white-space: nowrap;
        }

        .tc-button svg {
            width: 17px;
            height: 17px;
            stroke-width: 2;
        }

        .tc-button-primary {
            border: 1px solid var(--tc-cyan);
            background: var(--tc-cyan);
            color: #151419 !important;
        }

        .tc-button-primary:hover {
            transform: translateY(-2px);
            background: #39e5e9;
            box-shadow: 0 9px 25px rgba(8,213,219,.16);
        }

        .tc-page {
            min-height: calc(100vh - 100px);
            padding: 35px 0 70px;
            background:
                radial-gradient(ellipse at 90% 0%, rgba(8,213,219,.045), transparent 35%),
                var(--tc-bg);
        }

        .tc-container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 28px;
        }

        .tc-intro,
        .tc-collection-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }

        .tc-intro {
            margin-bottom: 26px;
        }

        .tc-intro h2,
        .tc-collection-heading h2 {
            color: var(--tc-text);
            font-family: var(--tc-display);
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -.7px;
        }

        .tc-intro p,
        .tc-collection-heading p {
            margin-top: 6px;
            color: var(--tc-muted);
            font-size: 11px;
        }

        .tc-workspace-chip {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            max-width: 260px;
            padding: 9px 13px;
            border: 1px solid var(--tc-border);
            border-radius: 30px;
            background: rgba(255,255,255,.025);
            color: var(--tc-muted);
            font-size: 10px;
        }

        .tc-workspace-chip span:last-child {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tc-workspace-indicator {
            width: 7px;
            height: 7px;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--tc-cyan);
            box-shadow: 0 0 10px rgba(8,213,219,.5);
        }

        .tc-alert {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 16px;
            padding: 14px 16px;
            border: 1px solid;
            border-radius: 10px;
            font-size: 12px;
        }

        .tc-alert svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .tc-alert-success {
            border-color: rgba(181,255,88,.2);
            background: rgba(181,255,88,.07);
            color: var(--tc-lime);
        }

        .tc-alert-error {
            border-color: rgba(255,91,25,.2);
            background: rgba(255,91,25,.07);
            color: #ff9b78;
        }

        .tc-alert ul {
            padding-left: 16px;
            list-style: disc;
        }

        .tc-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 42px;
        }

        .tc-stat-card {
            min-height: 140px;
            padding: 20px;
            border: 1px solid var(--tc-border);
            border-radius: 13px;
            background: linear-gradient(145deg, #201f26, #1b1a20);
            transition: .25s ease;
        }

        .tc-stat-card:hover {
            transform: translateY(-3px);
            border-color: rgba(8,213,219,.25);
        }

        .tc-stat-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--tc-muted);
            font-size: 11px;
        }

        .tc-stat-icon {
            display: grid;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 10px;
        }

        .tc-stat-icon svg {
            width: 18px;
            height: 18px;
        }

        .tc-stat-icon.cyan {
            color: var(--tc-cyan);
            background: rgba(8,213,219,.1);
        }

        .tc-stat-icon.lime {
            color: var(--tc-lime);
            background: rgba(181,255,88,.1);
        }

        .tc-stat-icon.orange {
            color: var(--tc-orange);
            background: rgba(255,91,25,.1);
        }

        .tc-stat-value {
            margin-top: 13px;
            color: var(--tc-text);
            font-family: var(--tc-display);
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .tc-stat-value.lime-text {
            color: var(--tc-lime);
        }

        .tc-stat-value.orange-text {
            color: var(--tc-orange);
        }

        .tc-stat-caption {
            display: block;
            margin-top: 9px;
            color: #77757f;
            font-size: 10px;
        }

        .tc-collection-heading {
            margin-bottom: 19px;
        }

        .tc-collection-heading .tc-eyebrow {
            margin-bottom: 5px;
        }

        .tc-count {
            padding: 7px 12px;
            border: 1px solid var(--tc-border);
            border-radius: 30px;
            color: var(--tc-muted);
            font-size: 10px;
            white-space: nowrap;
        }

        .tc-card-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 19px;
        }

        .tc-business-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--tc-border);
            border-radius: 15px;
            background: var(--tc-panel);
            transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
        }

        .tc-business-card:hover {
            transform: translateY(-5px);
            border-color: rgba(8,213,219,.3);
            box-shadow: 0 18px 40px rgba(0,0,0,.2);
        }

        .tc-card-cover {
            position: relative;
            height: 145px;
            overflow: hidden;
            background: #24232a;
        }

        .tc-card-cover > img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .tc-business-card:hover .tc-card-cover > img {
            transform: scale(1.04);
        }

        .tc-cover-placeholder {
            position: relative;
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 80% 20%, rgba(8,213,219,.2), transparent 50%),
                linear-gradient(135deg, #25232d, #101015);
        }

        .tc-cover-placeholder > span {
            position: relative;
            z-index: 2;
            color: rgba(255,255,255,.2);
            font-family: var(--tc-display);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 5px;
        }

        .tc-cover-orbit {
            position: absolute;
            top: -95px;
            right: -35px;
            width: 230px;
            height: 230px;
            border: 1px solid rgba(8,213,219,.2);
            border-radius: 50%;
        }

        .tc-cover-orbit::after {
            position: absolute;
            inset: 22px;
            border: 1px solid rgba(8,213,219,.12);
            border-radius: 50%;
            content: "";
        }

        .tc-cover-orbit-two {
            top: auto;
            right: auto;
            bottom: -160px;
            left: -80px;
            width: 250px;
            height: 250px;
            border-color: rgba(181,255,88,.12);
        }

        .tc-card-badges {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 6px;
        }

        .tc-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border: 1px solid transparent;
            border-radius: 30px;
            backdrop-filter: blur(12px);
            font-size: 9px;
            font-weight: 800;
        }

        .tc-badge-primary {
            border-color: rgba(255,235,0,.25);
            background: rgba(21,20,25,.88);
            color: var(--tc-yellow);
        }

        .tc-badge-primary svg {
            width: 11px;
            height: 11px;
        }

        .tc-badge-published {
            border-color: rgba(181,255,88,.2);
            background: rgba(21,20,25,.88);
            color: var(--tc-lime);
        }

        .tc-badge-draft {
            border-color: rgba(255,91,25,.2);
            background: rgba(21,20,25,.88);
            color: #ff9b78;
        }

        .tc-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .tc-card-body {
            padding: 0 19px 19px;
        }

        .tc-profile-row {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            min-width: 0;
            margin-top: -34px;
            margin-bottom: 15px;
        }

        .tc-avatar {
            display: grid;
            width: 72px;
            height: 72px;
            flex-shrink: 0;
            place-items: center;
            border: 4px solid var(--tc-panel);
            border-radius: 16px;
            background: #292831;
            object-fit: cover;
        }

        .tc-avatar-placeholder {
            color: var(--tc-cyan);
            font-family: var(--tc-display);
            font-size: 25px;
            font-weight: 800;
        }

        .tc-person-details {
            min-width: 0;
            padding-bottom: 3px;
        }

        .tc-person-details h3 {
            overflow: hidden;
            color: var(--tc-text);
            font-family: var(--tc-display);
            font-size: 17px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tc-person-details p {
            display: -webkit-box;
            overflow: hidden;
            margin-top: 4px;
            color: var(--tc-muted);
            font-size: 10px;
            line-height: 1.6;
            overflow-wrap: anywhere;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .tc-person-details p span {
            color: var(--tc-cyan);
        }

        .tc-card-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 18px;
            padding: 14px 0;
            border-top: 1px solid var(--tc-border);
            border-bottom: 1px solid var(--tc-border);
        }

        .tc-detail {
            min-width: 0;
        }

        .tc-detail span {
            display: block;
            margin-bottom: 6px;
            color: #77757f;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .tc-detail strong {
            display: block;
            overflow: hidden;
            color: var(--tc-text);
            font-size: 10px;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tc-card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 16px;
        }

        .tc-action {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 9px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
            transition: .2s ease;
        }

        .tc-action svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        .tc-action-secondary {
            border-color: rgba(255,255,255,.12);
            background: transparent;
            color: var(--tc-text);
        }

        .tc-action-secondary:hover {
            border-color: var(--tc-cyan);
            color: var(--tc-cyan);
        }

        .tc-action-primary {
            border-color: var(--tc-cyan);
            background: var(--tc-cyan);
            color: #151419;
        }

        .tc-action-primary:hover {
            border-color: #39e5e9;
            background: #39e5e9;
        }

        .tc-empty-state {
            display: flex;
            min-height: 370px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 45px 22px;
            border: 1px dashed rgba(255,255,255,.14);
            border-radius: 16px;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(8,213,219,.07), transparent 55%),
                var(--tc-panel);
            text-align: center;
        }

        .tc-empty-icon {
            display: grid;
            width: 72px;
            height: 72px;
            margin-bottom: 23px;
            place-items: center;
            border: 1px solid rgba(8,213,219,.2);
            border-radius: 20px;
            background: rgba(8,213,219,.08);
            color: var(--tc-cyan);
        }

        .tc-empty-icon svg {
            width: 32px;
            height: 32px;
        }

        .tc-empty-state h3 {
            color: var(--tc-text);
            font-family: var(--tc-display);
            font-size: 23px;
            font-weight: 700;
        }

        .tc-empty-state p {
            max-width: 430px;
            margin: 10px 0 23px;
            color: var(--tc-muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .tc-pagination {
            margin-top: 30px;
            color: var(--tc-text);
        }

        .tc-pagination nav {
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .tc-pagination nav > div:first-child {
            color: var(--tc-muted);
            font-size: 11px;
        }

        .tc-pagination nav > div:last-child {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .tc-pagination a,
        .tc-pagination span[aria-current="page"] span,
        .tc-pagination span[aria-disabled="true"] span {
            display: inline-flex;
            min-width: 35px;
            min-height: 35px;
            align-items: center;
            justify-content: center;
            padding: 0 9px;
            border: 1px solid var(--tc-border);
            border-radius: 8px;
            background: var(--tc-panel);
            color: var(--tc-muted);
            font-size: 11px;
            text-decoration: none;
        }

        .tc-pagination a:hover {
            border-color: var(--tc-cyan);
            color: var(--tc-cyan);
        }

        .tc-pagination span[aria-current="page"] span {
            border-color: var(--tc-cyan);
            background: var(--tc-cyan);
            color: #151419;
        }

        @media (max-width: 1050px) {
            .tc-card-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .tc-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .tc-header .tc-button {
                width: 100%;
            }

            .tc-container {
                padding: 0 17px;
            }

            .tc-intro {
                align-items: flex-start;
            }

            .tc-workspace-chip {
                display: none;
            }

            .tc-stats {
                gap: 10px;
            }

            .tc-stat-card {
                padding: 15px;
            }
        }

        @media (max-width: 560px) {
            .tc-card-grid {
                grid-template-columns: 1fr;
            }

            .tc-stats {
                grid-template-columns: 1fr 1fr;
            }

            .tc-stat-card:first-child {
                grid-column: span 2;
            }

            .tc-intro,
            .tc-collection-heading {
                align-items: flex-start;
            }

            .tc-intro h2,
            .tc-collection-heading h2 {
                font-size: 19px;
            }

            .tc-count {
                font-size: 9px;
            }

            .tc-pagination nav {
                flex-direction: column;
            }

            .tc-empty-state {
                min-height: 330px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .tc-page *,
            .tc-page *::before,
            .tc-page *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
</x-app-layout>
