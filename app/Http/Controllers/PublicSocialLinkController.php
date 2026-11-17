<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use App\Models\CardSocialLink;
use App\Services\CardAnalyticsService;
use Illuminate\Http\Request;

class PublicSocialLinkController extends Controller
{
    public function redirect(
        Request $request,
        string $slug,
        CardSocialLink $socialLink,
        CardAnalyticsService $analytics
    ) {
        $businessCard = BusinessCard::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->firstOrFail();

        abort_unless(
            (int) $socialLink->business_card_id === (int) $businessCard->id,
            404
        );

        abort_unless($socialLink->is_visible, 404);

        $analytics->track(
            $businessCard,
            'link_click',
            $request,
            'social',
            [
                'platform' => $socialLink->platform,
                'label' => $socialLink->label,
                'social_link_id' => $socialLink->id,
            ]
        );

        $url = $socialLink->url;

        // Only redirect to HTTP(S) URLs.
        abort_unless(
            in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true),
            404
        );

        return redirect()->away($url);
    }
}
