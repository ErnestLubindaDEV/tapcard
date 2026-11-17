<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use App\Models\CardSocialLink;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CardSocialLinkController extends Controller
{
    private array $platforms = [
        'facebook',
        'instagram',
        'linkedin',
        'twitter',
        'tiktok',
        'youtube',
        'github',
        'whatsapp',
        'telegram',
        'custom',
    ];

    private function authorizeCardAccess(
        Workspace $workspace,
        BusinessCard $businessCard
    ): void {
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
            'You do not have permission to manage this card.'
        );
    }

    public function index(
        Workspace $workspace,
        BusinessCard $businessCard
    ) {
        $this->authorizeCardAccess($workspace, $businessCard);

        $socialLinks = $businessCard->socialLinks()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('business-cards.social-links', [
            'workspace' => $workspace,
            'businessCard' => $businessCard,
            'socialLinks' => $socialLinks,
            'platforms' => $this->platforms,
        ]);
    }

    public function store(
        Request $request,
        Workspace $workspace,
        BusinessCard $businessCard
    ) {
        $this->authorizeCardAccess($workspace, $businessCard);

        $validated = $request->validate([
            'platform' => [
                'required',
                Rule::in($this->platforms),
            ],
            'label' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:2048'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $businessCard->socialLinks()->create([
            'platform' => $validated['platform'],
            'label' => $validated['label'] ?? null,
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        return redirect()
            ->route('business-cards.social-links.index', [
                'workspace' => $workspace->id,
                'businessCard' => $businessCard->id,
            ])
            ->with('success', 'Social media link added successfully.');
    }

    public function update(
        Request $request,
        Workspace $workspace,
        BusinessCard $businessCard,
        CardSocialLink $socialLink
    ) {
        $this->authorizeCardAccess($workspace, $businessCard);

        abort_unless(
            (int) $socialLink->business_card_id === (int) $businessCard->id,
            404
        );

        $validated = $request->validate([
            'platform' => [
                'required',
                Rule::in($this->platforms),
            ],
            'label' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:2048'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $socialLink->update([
            'platform' => $validated['platform'],
            'label' => $validated['label'] ?? null,
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        return redirect()
            ->route('business-cards.social-links.index', [
                'workspace' => $workspace->id,
                'businessCard' => $businessCard->id,
            ])
            ->with('success', 'Social media link updated successfully.');
    }

    public function destroy(
        Workspace $workspace,
        BusinessCard $businessCard,
        CardSocialLink $socialLink
    ) {
        $this->authorizeCardAccess($workspace, $businessCard);

        abort_unless(
            (int) $socialLink->business_card_id === (int) $businessCard->id,
            404
        );

        $socialLink->delete();

        return redirect()
            ->route('business-cards.social-links.index', [
                'workspace' => $workspace->id,
                'businessCard' => $businessCard->id,
            ])
            ->with('success', 'Social media link removed successfully.');
    }
}
