<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use App\Services\CardAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PublicCardContactController extends Controller
{
    public function download(
        Request $request,
        string $slug,
        CardAnalyticsService $analytics
    ): Response {
        $card = BusinessCard::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->firstOrFail();

        $fullName = trim(
            $card->first_name . ' ' . ($card->last_name ?? '')
        );

        $publicUrl = route('cards.public', [
            'slug' => $card->slug,
        ]);

        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'PRODID:-//TapCard//Digital Business Card//EN',
            'N:' . $this->escape($card->last_name) . ';'
                . $this->escape($card->first_name) . ';;;',
            'FN:' . $this->escape($fullName),
        ];

        if ($card->company_name) {
            $lines[] = 'ORG:' . $this->escape($card->company_name);
        }

        if ($card->job_title) {
            $lines[] = 'TITLE:' . $this->escape($card->job_title);
        }

        if ($card->show_email && $card->email) {
            $lines[] = 'EMAIL;TYPE=INTERNET,WORK:'
                . $this->escape($card->email);
        }

        if ($card->show_phone && $card->phone) {
            $lines[] = 'TEL;TYPE=WORK,VOICE:'
                . $this->escape($card->phone);
        }

        if ($card->whatsapp) {
            $whatsappNumber = preg_replace(
                '/\D+/',
                '',
                $card->whatsapp
            );

            if ($whatsappNumber) {
                $lines[] = 'X-WHATSAPP:'
                    . $this->escape(
                        'https://wa.me/' . $whatsappNumber
                    );
            }
        }

        if ($card->website) {
            $website = trim($card->website);

            if (!preg_match('/^https?:\/\//i', $website)) {
                $website = 'https://' . $website;
            }

            $lines[] = 'URL:' . $this->escape($website);
        }

        if ($card->address || $card->city || $card->country) {
            $lines[] = 'ADR;TYPE=WORK:;;'
                . $this->escape($card->address) . ';'
                . $this->escape($card->city) . ';;;'
                . $this->escape($card->country);
        }

        if ($card->bio) {
            $lines[] = 'NOTE:' . $this->escape($card->bio);
        }

        $lines[] = 'X-TAPCARD-URL:' . $this->escape($publicUrl);
        $lines[] = 'END:VCARD';

        $content = implode("\r\n", $lines) . "\r\n";

        $filename = Str::slug($fullName ?: 'tapcard-contact')
            . '-contact.vcf';

        $analytics->track(
            $card,
            'contact_save',
            $request,
            'contact'
        );

        return response($content, 200, [
            'Content-Type' => 'text/vcard; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, private',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function escape(?string $value): string
    {
        $value = $value ?? '';

        $value = str_replace('\\', '\\\\', $value);
        $value = str_replace(';', '\;', $value);
        $value = str_replace(',', '\,', $value);

        return str_replace(
            ["\r\n", "\r", "\n"],
            '\n',
            $value
        );
    }
}
