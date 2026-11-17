<?php

namespace App\Services;

use App\Models\BusinessCard;
use App\Models\CardAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardAnalyticsService
{
    public function track(
        BusinessCard $card,
        string $eventType,
        Request $request,
        ?string $eventSource = null,
        array $metadata = []
    ): void {
        $allowedEvents = [
            'view',
            'qr_scan',
            'contact_save',
            'link_click',
        ];

        if (!in_array($eventType, $allowedEvents, true)) {
            return;
        }

        try {
            CardAnalytic::create([
                'workspace_id' => $card->workspace_id,
                'business_card_id' => $card->id,
                'event_type' => $eventType,
                'event_source' => $eventSource,
                'referrer' => $request->headers->get('referer'),
                'ip_hash' => $request->ip()
                    ? hash_hmac(
                        'sha256',
                        $request->ip(),
                        (string) config('app.key')
                    )
                    : null,
                'user_agent' => substr(
                    (string) $request->userAgent(),
                    0,
                    2000
                ),
                'metadata' => $metadata ?: null,
                'occurred_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            // Analytics must never prevent a public card from loading.
            Log::warning('TapCard analytics event could not be recorded.', [
                'business_card_id' => $card->id,
                'event_type' => $eventType,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
