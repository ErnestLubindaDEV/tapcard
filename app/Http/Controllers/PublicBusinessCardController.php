<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use App\Services\CardAnalyticsService;
use Illuminate\Http\Request;

class PublicBusinessCardController extends Controller
{
    public function show(
        Request $request,
        string $slug,
        CardAnalyticsService $analytics
    ) {
        $businessCard = BusinessCard::query()
            ->with([
                'theme',
                'socialLinks',
            ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->firstOrFail();

        $source = $request->query('source');

        $eventSource = $source === 'qr' ? 'qr' : 'direct';

        if ($source === 'qr') {
            $analytics->track(
                $businessCard,
                'qr_scan',
                $request,
                'qr'
            );
        } else {
            $analytics->track(
                $businessCard,
                'view',
                $request,
                $eventSource
            );
        }

        return view('business-cards.public', compact('businessCard'));
    }
}
