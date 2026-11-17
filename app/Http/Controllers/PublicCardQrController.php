<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use chillerlan\QRCode\QRCode;
use Illuminate\Http\Response;

class PublicCardQrController extends Controller
{
    /**
     * Display the QR code for a published public card.
     */
    public function show(string $slug)
    {
        $businessCard = $this->findPublicCard($slug);

        $publicUrl = route('cards.public', [
            'slug' => $businessCard->slug,
            'source' => 'qr',
        ]);

        $qrCode = (new QRCode())->render($publicUrl);

        return view('business-cards.qr-code', compact(
            'businessCard',
            'publicUrl',
            'qrCode'
        ));
    }

    /**
     * Download the QR code as an SVG file.
     */
    public function download(string $slug): Response
    {
        $businessCard = $this->findPublicCard($slug);

        $publicUrl = route('cards.public', [
            'slug' => $businessCard->slug,
            'source' => 'qr',
        ]);

        $qrCode = (new QRCode())->render($publicUrl);

        if (!preg_match('/^data:image\/svg\+xml;base64,(.+)$/s', $qrCode, $matches)) {
            abort(500, 'Unable to generate the QR code.');
        }

        $svg = base64_decode($matches[1], true);

        if ($svg === false) {
            abort(500, 'Unable to decode the QR code.');
        }

        $filename = \Illuminate\Support\Str::slug($businessCard->full_name)
            . '-tapcard-qr.svg';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Only published and public cards receive QR codes.
     */
    private function findPublicCard(string $slug): BusinessCard
    {
        return BusinessCard::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->firstOrFail();
    }
}
