@extends('layouts.app')

@section('title', 'Overview')

@php
$cards = collect($businessCards ?? []);

$totalCards = $dashboardStats['total_cards'] ?? $cards->count();
$publishedCards = $dashboardStats['published_cards'] ?? $cards->where('status', 'published')->count();
$totalViews = $dashboardStats['total_views'] ?? null;
$totalScans = $dashboardStats['qr_scans'] ?? null;
$totalContacts = $dashboardStats['contact_saves'] ?? null;

$hasWorkspace = isset($workspace) && $workspace;
@endphp

@push('styles')
<style>
    .dashboard-welcome {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .dashboard-welcome h1 {
        margin: 0;
        font-family: 'Outfit', sans-serif;
        font-size: clamp(30px, 3.4vw, 43px);
        font-weight: 500;
        letter-spacing: -1.8px;
        line-height: 1.1;
    }

    .dashboard-welcome p {
        margin: 9px 0 0;
        color: #718078;
        font-size: 13px;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .dashboard-stat {
        min-height: 112px;
        padding: 17px 18px;
        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 18px;
        background: rgba(255, 255, 255, .57);
        box-shadow: 0 8px 24px rgba(31, 75, 42, .035);
    }

    .dashboard-stat-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        color: #68776C;
        font-size: 11px;
        font-weight: 600;
    }

    .dashboard-stat-value {
        margin-top: 9px;
        font-family: 'Outfit', sans-serif;
        font-size: clamp(27px, 3vw, 38px);
        font-weight: 500;
        letter-spacing: -1.5px;
        line-height: 1;
        color: var(--tap-dark);
    }

    .dashboard-stat-note {
        margin-top: 8px;
        color: #829087;
        font-size: 10px;
    }

    .dashboard-stat-icon {
        width: 29px;
        height: 29px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(8, 213, 219, .13);
        color: #078D92;
        font-size: 13px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 1.05fr) minmax(270px, .85fr);
        gap: 13px;
        align-items: stretch;
    }

    .dashboard-card-preview {
        position: relative;
        min-height: 250px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        padding: 21px;
        border-radius: 21px;
        background:
            radial-gradient(circle at 85% 10%, rgba(8, 213, 219, .3), transparent 38%),
            linear-gradient(145deg, #24222C, #151419 72%);
        color: #fff;
    }

    .dashboard-card-preview::after {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        right: -90px;
        bottom: -100px;
        border: 1px solid rgba(8, 213, 219, .3);
        border-radius: 50%;
        box-shadow: 0 0 0 22px rgba(8, 213, 219, .035), 0 0 0 45px rgba(8, 213, 219, .025);
    }

    .dashboard-card-preview-top,
    .dashboard-card-preview-bottom {
        position: relative;
        z-index: 1;
    }

    .dashboard-card-preview-logo {
        font-family: 'Outfit', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--tap-cyan);
    }

    .dashboard-card-preview h3 {
        margin: 0;
        font-family: 'Outfit', sans-serif;
        font-size: 25px;
        font-weight: 500;
        letter-spacing: -.7px;
    }

    .dashboard-card-preview p {
        margin: 5px 0 0;
        color: rgba(255, 255, 255, .65);
        font-size: 11px;
    }

    .dashboard-card-preview-url {
        margin-top: 17px;
        color: var(--tap-cyan);
        font-size: 10px;
    }

    .dashboard-card-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 17px;
    }

    .dashboard-chart {
        display: flex;
        height: 145px;
        align-items: flex-end;
        justify-content: space-around;
        gap: 12px;
        padding: 10px 4px 0;
        border-bottom: 1px solid rgba(21, 20, 25, .08);
    }

    .dashboard-chart-column {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        height: 100%;
        gap: 8px;
    }

    .dashboard-chart-bar {
        width: min(25px, 80%);
        min-height: 5px;
        border-radius: 999px;
        background: #8DBF94;
    }

    .dashboard-chart-bar.highlight {
        background: var(--tap-green-dark);
    }

    .dashboard-chart-label {
        color: #839087;
        font-size: 10px;
    }

    .dashboard-progress-ring {
        width: 150px;
        height: 150px;
        margin: 8px auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: conic-gradient(var(--tap-green-dark) 0deg 250deg,
                rgba(104, 169, 121, .15) 250deg 360deg);
        position: relative;
    }

    .dashboard-progress-ring::before {
        content: "";
        position: absolute;
        inset: 10px;
        border-radius: 50%;
        background: #F5FAF4;
    }

    .dashboard-progress-inner {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .dashboard-progress-inner strong {
        display: block;
        font-family: 'Outfit', sans-serif;
        font-size: 29px;
        font-weight: 500;
        letter-spacing: -1px;
    }

    .dashboard-progress-inner span {
        display: block;
        margin-top: 3px;
        color: #78857B;
        font-size: 10px;
    }

    .dashboard-activity {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .dashboard-activity-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(21, 20, 25, .07);
    }

    .dashboard-activity-item:last-child {
        border-bottom: 0;
    }

    .dashboard-activity-icon {
        width: 35px;
        height: 35px;
        flex: 0 0 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(8, 213, 219, .12);
        color: #078D92;
        font-size: 13px;
    }

    .dashboard-activity-copy {
        min-width: 0;
        flex: 1;
    }

    .dashboard-activity-copy strong {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--tap-dark);
    }

    .dashboard-activity-copy span {
        display: block;
        margin-top: 3px;
        color: #8A958C;
        font-size: 10px;
    }

    .dashboard-task-list {
        display: flex;
        flex-direction: column;
        gap: 13px;
        margin-top: 18px;
    }

    .dashboard-task {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: rgba(255, 255, 255, .82);
        font-size: 11px;
    }

    .dashboard-task-check {
        width: 19px;
        height: 19px;
        flex: 0 0 19px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(8, 213, 219, .17);
        color: var(--tap-cyan);
        font-size: 10px;
    }

    .dashboard-lower-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(250px, .9fr);
        gap: 13px;
        margin-top: 13px;
    }

    .dashboard-card-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(21, 20, 25, .07);
    }

    .dashboard-card-row:last-child {
        border-bottom: 0;
    }

    .dashboard-card-avatar {
        width: 39px;
        height: 39px;
        flex: 0 0 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--tap-dark);
        color: var(--tap-cyan);
        font-family: 'Outfit', sans-serif;
        font-size: 17px;
        font-weight: 700;
    }

    .dashboard-card-row-info {
        flex: 1;
        min-width: 0;
    }

    .dashboard-card-row-info strong {
        display: block;
        overflow: hidden;
        color: var(--tap-dark);
        font-size: 12px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dashboard-card-row-info span {
        display: block;
        margin-top: 4px;
        color: #89948C;
        font-size: 10px;
    }

    .dashboard-quick-action {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px;
        border: 1px solid rgba(21, 20, 25, .06);
        border-radius: 13px;
        background: rgba(255, 255, 255, .58);
        color: var(--tap-dark);
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .dashboard-quick-action:hover {
        background: #fff;
        transform: translateY(-1px);
    }

    .dashboard-quick-action-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(8, 213, 219, .13);
        color: #078D92;
        font-size: 15px;
    }

    @media (max-width: 1100px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid> :last-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 720px) {
        .dashboard-welcome {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid,
        .dashboard-lower-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-grid> :last-child {
            grid-column: auto;
        }
    }

    @media (max-width: 390px) {
        .dashboard-stats {
            gap: 8px;
        }

        .dashboard-stat {
            padding: 13px;
        }
    }

    /* =========================================
   TAPCARD BLACK DASHBOARD THEME
========================================= */

    .dashboard-welcome h1 {
        color: #FFFFFF;
    }

    .dashboard-welcome p {
        color: #92939D;
    }

    .dashboard-stat {
        background: #151519;
        border: 1px solid rgba(255, 255, 255, .08);
        box-shadow: 0 12px 30px rgba(0, 0, 0, .16);
    }

    .dashboard-stat-label {
        color: #A0A1AA;
    }

    .dashboard-stat-value {
        color: #FFFFFF;
    }

    .dashboard-stat-note {
        color: #777883;
    }

    .dashboard-stat-icon {
        background: rgba(8, 213, 219, .12);
        color: #08D5DB;
    }

    .dashboard-card-preview {
        background:
            radial-gradient(circle at 85% 10%, rgba(8, 213, 219, .22), transparent 38%),
            linear-gradient(145deg, #202027, #08090B 75%);
        border: 1px solid rgba(8, 213, 219, .12);
    }

    .dashboard-chart {
        border-bottom-color: rgba(255, 255, 255, .1);
    }

    .dashboard-chart-bar {
        background: rgba(8, 213, 219, .35);
    }

    .dashboard-chart-bar.highlight {
        background: #08D5DB;
    }

    .dashboard-chart-label {
        color: #85858F;
    }

    .dashboard-progress-ring {
        background: conic-gradient(#08D5DB 0deg 250deg,
                rgba(255, 255, 255, .08) 250deg 360deg);
    }

    .dashboard-progress-ring::before {
        background: #151519;
    }

    .dashboard-progress-inner strong {
        color: #FFFFFF;
    }

    .dashboard-progress-inner span {
        color: #92939D;
    }

    .dashboard-activity-item {
        border-bottom-color: rgba(255, 255, 255, .08);
    }

    .dashboard-activity-icon {
        background: rgba(8, 213, 219, .12);
        color: #08D5DB;
    }

    .dashboard-activity-copy strong {
        color: #F5F5F7;
    }

    .dashboard-activity-copy span {
        color: #85858F;
    }

    .dashboard-card-row {
        border-bottom-color: rgba(255, 255, 255, .08);
    }

    .dashboard-card-avatar {
        background: #08D5DB;
        color: #08090B;
    }

    .dashboard-card-row-info strong {
        color: #FFFFFF;
    }

    .dashboard-card-row-info span {
        color: #85858F;
    }

    .dashboard-quick-action {
        background: #1B1B21;
        border-color: rgba(255, 255, 255, .08);
        color: #F5F5F7;
    }

    .dashboard-quick-action:hover {
        background: #24242B;
        border-color: rgba(8, 213, 219, .3);
    }

    .dashboard-quick-action-icon {
        background: rgba(8, 213, 219, .12);
        color: #08D5DB;
    }

    .dashboard-task {
        color: rgba(255, 255, 255, .8);
    }

    .dashboard-task-check {
        background: rgba(8, 213, 219, .15);
        color: #08D5DB;
    }

    /* Dashboard panels */
    .tap-panel {
        background: #151519;
        border-color: rgba(255, 255, 255, .08);
        box-shadow: 0 12px 30px rgba(0, 0, 0, .15);
    }

    .tap-panel-title {
        color: #FFFFFF;
    }

    /* Dashboard alignment */
    .dashboard-welcome {
        align-items: center;
    }

    .dashboard-stats {
        align-items: stretch;
    }

    .dashboard-stat {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .dashboard-grid>*,
    .dashboard-lower-grid>* {
        min-width: 0;
    }

    /* Responsive dashboard */
    @media (max-width: 1100px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .dashboard-welcome {
            align-items: flex-start;
        }

        .dashboard-grid,
        .dashboard-lower-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .dashboard-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 390px) {
        .dashboard-stats {
            grid-template-columns: minmax(0, 1fr);
        }
    }
</style>
@endpush


@section('content')
<div>

    <!-- WELCOME HEADING -->
    <section class="dashboard-welcome">
        <div>
            <h1>Welcome back, {{ Str::of(Auth::user()->name)->before(' ') }}.</h1>
            <p>Here's an overview of your TapCard digital identity.</p>
        </div>

        <div style="display:flex;gap:9px;flex-wrap:wrap;">
            <a href="{{ route('workspaces.index') }}" class="tap-button tap-button-light">
                Workspaces
            </a>

            @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.create'))
            <a
                href="{{ route('business-cards.create', ['workspace' => $workspace->id]) }}"
                class="tap-button">
                <span>＋</span> Create a card
            </a>
            @else
            <a href="{{ route('workspaces.index') }}" class="tap-button">
                <span>＋</span> Get started
            </a>
            @endif
        </div>
    </section>

    <!-- METRICS -->
    <section class="dashboard-stats">

        <div class="dashboard-stat">
            <div class="dashboard-stat-label">
                Total cards
                <span class="dashboard-stat-icon">▣</span>
            </div>
            <div class="dashboard-stat-value">{{ number_format($totalCards) }}</div>
            <div class="dashboard-stat-note">Cards in your workspace</div>
        </div>

        <div class="dashboard-stat">
            <div class="dashboard-stat-label">
                Published cards
                <span class="dashboard-stat-icon">↗</span>
            </div>
            <div class="dashboard-stat-value">{{ number_format($publishedCards) }}</div>
            <div class="dashboard-stat-note">Available to share publicly</div>
        </div>

        <div class="dashboard-stat">
            <div class="dashboard-stat-label">
                Profile views
                <span class="dashboard-stat-icon">◉</span>
            </div>
            <div class="dashboard-stat-value">
                {{ $totalViews !== null ? number_format($totalViews) : '—' }}
            </div>
            <div class="dashboard-stat-note">Analytics data</div>
        </div>

        <div class="dashboard-stat">
            <div class="dashboard-stat-label">
                Contact saves
                <span class="dashboard-stat-icon">♧</span>
            </div>
            <div class="dashboard-stat-value">
                {{ $totalContacts !== null ? number_format($totalContacts) : '—' }}
            </div>
            <div class="dashboard-stat-note">People who saved a contact</div>
        </div>

    </section>

    <!-- MAIN DASHBOARD GRID -->
    <section class="dashboard-grid">

        <!-- FEATURED CARD -->
        <div class="tap-panel" style="padding:13px;">
            <div class="dashboard-card-heading" style="padding:5px 7px 0;">
                <h2 class="tap-panel-title">Your digital identity</h2>
                <span class="tap-badge tap-badge-green">TapCard</span>
            </div>

            <div class="dashboard-card-preview">
                <div class="dashboard-card-preview-top">
                    <div class="dashboard-card-preview-logo">T.</div>
                    <span style="float:right;font-size:10px;color:rgba(255,255,255,.55);">
                        DIGITAL BUSINESS CARD
                    </span>
                </div>

                <div class="dashboard-card-preview-bottom">
                    <h3>{{ Auth::user()->name }}</h3>
                    <p>Your professional identity, one tap away.</p>
                    <div class="dashboard-card-preview-url">
                        Create and publish your first digital card
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;padding:14px 5px 2px;">
                <span class="tap-muted" style="font-size:11px;">Make your first impression count.</span>
                @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.create'))
                <a href="{{ route('business-cards.create', ['workspace' => $workspace->id]) }}" style="font-size:11px;font-weight:700;color:#078D92;text-decoration:none;">
                    Create card →
                </a>
                @else
                <a href="{{ route('workspaces.index') }}" style="font-size:11px;font-weight:700;color:#078D92;text-decoration:none;">
                    Get started →
                </a>
                @endif
            </div>
        </div>

        <!-- PERFORMANCE -->
        <div class="tap-panel">
            <div class="dashboard-card-heading">
                <div>
                    <h2 class="tap-panel-title">Card performance</h2>
                    <p class="tap-muted" style="font-size:10px;margin:5px 0 0;">
                        Your digital card engagement
                    </p>
                </div>
                <span class="tap-icon-button" aria-hidden="true">↗</span>
            </div>

            @if (isset($dashboardStats['daily_views']) && count($dashboardStats['daily_views']))
            <div class="dashboard-chart">
                @foreach ($dashboardStats['daily_views'] as $day)
                @php
                $maxViews = max(1, collect($dashboardStats['daily_views'])->max('views'));
                $height = ($day['views'] / $maxViews) * 100;
                @endphp

                <div class="dashboard-chart-column">
                    <div
                        class="dashboard-chart-bar {{ $loop->last ? 'highlight' : '' }}"
                        style="height:{{ max(5, $height) }}%;"
                        title="{{ $day['views'] }} views"></div>
                    <span class="dashboard-chart-label">{{ $day['label'] }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="tap-empty">
                <div class="tap-empty-icon">⌁</div>
                <strong style="display:block;color:var(--tap-dark);font-size:12px;margin-bottom:5px;">
                    Performance insights
                </strong>
                <span>Analytics will appear here as visitors interact with your published cards.</span>
            </div>
            @endif

            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px;">
                <span class="tap-muted" style="font-size:10px;">QR scans</span>
                <strong style="font-family:'Outfit',sans-serif;font-size:17px;">
                    {{ $totalScans !== null ? number_format($totalScans) : '—' }}
                </strong>
            </div>
        </div>

        <!-- PROFILE COMPLETION -->
        <div class="tap-panel">
            <div class="dashboard-card-heading">
                <h2 class="tap-panel-title">Getting started</h2>
                <span class="tap-badge tap-badge-green">Your checklist</span>
            </div>

            <div class="dashboard-progress-ring">
                <div class="dashboard-progress-inner">
                    <strong>{{ $hasWorkspace ? '50%' : '0%' }}</strong>
                    <span>Setup progress</span>
                </div>
            </div>

            <div class="dashboard-task-list">
                <div class="dashboard-task" style="color:var(--tap-dark);">
                    <span class="dashboard-task-check">✓</span>
                    <span>Account created</span>
                </div>

                <div class="dashboard-task" style="color:var(--tap-dark);">
                    <span class="dashboard-task-check">{{ $hasWorkspace ? '✓' : '○' }}</span>
                    <span>Create your workspace</span>
                </div>

                <div class="dashboard-task" style="color:var(--tap-dark);">
                    <span class="dashboard-task-check">{{ $totalCards > 0 ? '✓' : '○' }}</span>
                    <span>Create your first digital card</span>
                </div>

                <div class="dashboard-task" style="color:var(--tap-dark);">
                    <span class="dashboard-task-check">{{ $publishedCards > 0 ? '✓' : '○' }}</span>
                    <span>Publish and share your card</span>
                </div>
            </div>

            <div style="margin-top:20px;">
                <a href="{{ route('workspaces.index') }}" class="tap-button tap-button-accent" style="width:100%;">
                    Continue setup →
                </a>
            </div>
        </div>

    </section>

    <!-- LOWER SECTION -->
    <section class="dashboard-lower-grid">

        <!-- YOUR CARDS -->
        <div class="tap-panel">
            <div class="dashboard-card-heading">
                <div>
                    <h2 class="tap-panel-title">Your cards</h2>
                    <p class="tap-muted" style="font-size:10px;margin:5px 0 0;">
                        Manage your digital business cards
                    </p>
                </div>

                @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.index'))
                <a href="{{ route('business-cards.index', ['workspace' => $workspace->id]) }}" style="font-size:11px;color:#078D92;font-weight:700;text-decoration:none;">
                    View all →
                </a>
                @endif
            </div>

            @forelse ($cards->take(4) as $card)
            <div class="dashboard-card-row">
                <div class="dashboard-card-avatar">
                    {{ strtoupper(substr($card->first_name ?? 'T', 0, 1)) }}
                </div>

                <div class="dashboard-card-row-info">
                    <strong>{{ $card->full_name ?? trim(($card->first_name ?? '') . ' ' . ($card->last_name ?? '')) }}</strong>
                    <span>{{ $card->job_title ?: 'Digital business card' }}</span>
                </div>

                <span class="tap-badge {{ $card->status === 'published' ? 'tap-badge-green' : '' }}">
                    {{ ucfirst($card->status ?? 'Draft') }}
                </span>

                @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.show'))
                <a
                    href="{{ route('business-cards.show', [
                                'workspace' => $workspace->id,
                                'businessCard' => $card->id
                            ]) }}"
                    class="tap-icon-button"
                    aria-label="View card">
                    →
                </a>
                @endif
            </div>
            @empty
            <div class="tap-empty">
                <div class="tap-empty-icon">▣</div>
                <strong style="display:block;color:var(--tap-dark);font-size:12px;margin-bottom:5px;">
                    No digital cards yet
                </strong>
                <span>Create your first card to start sharing your professional identity.</span>

                <div style="margin-top:16px;">
                    @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.create'))
                    <a href="{{ route('business-cards.create', ['workspace' => $workspace->id]) }}" class="tap-button">
                        Create your first card
                    </a>
                    @else
                    <a href="{{ route('workspaces.index') }}" class="tap-button">
                        Create a workspace
                    </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        <!-- QUICK ACTIONS -->
        <div class="tap-panel">
            <div class="dashboard-card-heading">
                <h2 class="tap-panel-title">Quick actions</h2>
            </div>

            <div style="display:flex;flex-direction:column;gap:9px;">
                @if ($hasWorkspace && \Illuminate\Support\Facades\Route::has('business-cards.create'))
                <a href="{{ route('business-cards.create', ['workspace' => $workspace->id]) }}" class="dashboard-quick-action">
                    <span class="dashboard-quick-action-icon">＋</span>
                    <span style="flex:1;">Create a business card</span>
                    <span>→</span>
                </a>

                <a href="{{ route('business-cards.index', ['workspace' => $workspace->id]) }}" class="dashboard-quick-action">
                    <span class="dashboard-quick-action-icon">▣</span>
                    <span style="flex:1;">Manage your cards</span>
                    <span>→</span>
                </a>
                @endif

                <a href="{{ route('workspaces.index') }}" class="dashboard-quick-action">
                    <span class="dashboard-quick-action-icon">▦</span>
                    <span style="flex:1;">Manage workspaces</span>
                    <span>→</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="dashboard-quick-action">
                    <span class="dashboard-quick-action-icon">⚙</span>
                    <span style="flex:1;">Account settings</span>
                    <span>→</span>
                </a>
            </div>

            <div style="margin-top:17px;padding:15px;border-radius:15px;background:var(--tap-dark);color:#fff;">
                <div style="font-family:'Outfit',sans-serif;font-size:15px;font-weight:500;">
                    Your identity. One tap away.
                </div>
                <p style="margin:6px 0 0;color:rgba(255,255,255,.62);font-size:10px;line-height:1.6;">
                    Make every introduction count with TapCard.
                </p>
            </div>
        </div>

    </section>

</div>
@endsection