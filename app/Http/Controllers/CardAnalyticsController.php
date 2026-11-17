<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use App\Models\CardAnalytic;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CardAnalyticsController extends Controller
{
    public function index(
        Request $request,
        Workspace $workspace,
        BusinessCard $businessCard
    ) {
        abort_unless(
            (int) $businessCard->workspace_id === (int) $workspace->id,
            404
        );

        $membership = WorkspaceMember::query()
            ->where('workspace_id', $workspace->id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        abort_unless(
            $membership && $membership->hasPermission('manage_cards'),
            403,
            'You do not have permission to view these analytics.'
        );

        $validated = $request->validate([
            'period' => ['nullable', 'in:7,30,90'],
        ]);

        $period = (int) ($validated['period'] ?? 30);

        $startDate = now()->subDays($period - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $analytics = CardAnalytic::query()
            ->where('business_card_id', $businessCard->id)
            ->whereBetween('occurred_at', [$startDate, $endDate]);

        $totalViews = (clone $analytics)
            ->where('event_type', 'view')
            ->count();

        $uniqueVisitors = (clone $analytics)
            ->where('event_type', 'view')
            ->whereNotNull('ip_hash')
            ->distinct('ip_hash')
            ->count('ip_hash');

        $qrScans = (clone $analytics)
            ->where('event_type', 'qr_scan')
            ->count();

        $contactSaves = (clone $analytics)
            ->where('event_type', 'contact_save')
            ->count();

        $linkClicks = (clone $analytics)
            ->where('event_type', 'link_click')
            ->count();

        $totalEngagement = $qrScans + $contactSaves + $linkClicks;

        $engagementRate = $totalViews > 0
            ? round(($totalEngagement / $totalViews) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Daily Views
        |--------------------------------------------------------------------------
        */

        $dailyViews = (clone $analytics)
            ->where('event_type', 'view')
            ->selectRaw('DATE(occurred_at) as date, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(occurred_at)'))
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartViews = [];

        for ($i = 0; $i < $period; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();

            $chartLabels[] = Carbon::parse($date)->format('d M');
            $chartViews[] = (int) ($dailyViews[$date] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | Social Link Performance
        |--------------------------------------------------------------------------
        */

        $platformClicks = (clone $analytics)
            ->where('event_type', 'link_click')
            ->selectRaw("
                JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.platform')) as platform,
                COUNT(*) as total
            ")
            ->groupBy('platform')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Traffic Sources
        |--------------------------------------------------------------------------
        */

        $trafficSources = (clone $analytics)
            ->selectRaw("
                COALESCE(event_source, 'direct') as source,
                COUNT(*) as total
            ")
            ->where('event_type', 'view')
            ->groupBy('event_source')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Activity
        |--------------------------------------------------------------------------
        */

        $recentActivity = (clone $analytics)
            ->latest('occurred_at')
            ->limit(15)
            ->get();

        return view('business-cards.analytics', [
            'workspace' => $workspace,
            'businessCard' => $businessCard,
            'period' => $period,

            'totalViews' => $totalViews,
            'uniqueVisitors' => $uniqueVisitors,
            'qrScans' => $qrScans,
            'contactSaves' => $contactSaves,
            'linkClicks' => $linkClicks,
            'totalEngagement' => $totalEngagement,
            'engagementRate' => $engagementRate,

            'chartLabels' => $chartLabels,
            'chartViews' => $chartViews,

            'platformClicks' => $platformClicks,
            'trafficSources' => $trafficSources,
            'recentActivity' => $recentActivity,
        ]);
    }
}
