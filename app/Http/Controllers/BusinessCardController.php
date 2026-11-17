<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use App\Models\CardTheme;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessCardController extends Controller
{
    /**
     * Display cards belonging to a workspace.
     */
    public function index(Workspace $workspace)
    {
        $membership = $this->authorizeCardAccess($workspace);

        $cards = BusinessCard::where('workspace_id', $workspace->id)
            ->with('theme')
            ->latest()
            ->paginate(12);

        return view('business-cards.index', compact(
            'workspace',
            'cards',
            'membership'
        ));
    }

    /**
     * Show the create card form.
     */
    public function create(Workspace $workspace)
    {
        $this->authorizeCardAccess($workspace);

        $themes = $this->availableThemes($workspace);

        return view('business-cards.create', compact(
            'workspace',
            'themes'
        ));
    }

    /**
     * Store a new business card.
     */
    public function store(Request $request, Workspace $workspace)
    {
        $this->authorizeCardAccess($workspace);

$validated = $this->validateCard($request, $workspace);

        DB::transaction(function () use ($request, $workspace, $validated) {
            $profilePhoto = $request->hasFile('profile_photo')
                ? $request->file('profile_photo')->store('business-cards/profiles', 'public')
                : null;

            $coverPhoto = $request->hasFile('cover_photo')
                ? $request->file('cover_photo')->store('business-cards/covers', 'public')
                : null;

            $card = BusinessCard::create([
                ...$validated,
                'workspace_id' => $workspace->id,
                'created_by' => Auth::id(),
                'slug' => BusinessCard::generateUniqueSlug(
                    $validated['first_name'] . ' ' . $validated['last_name']
                ),
                'profile_photo' => $profilePhoto,
                'cover_photo' => $coverPhoto,
                'status' => 'draft',
                'visibility' => $validated['visibility'] ?? 'public',
                'is_primary' => false,
                'published_at' => null,
            ]);
        });

        return redirect()
            ->route('business-cards.index', $workspace)
            ->with('success', 'Business card created successfully.');
    }

    /**
     * Display a card's management details.
     */
    public function show(Workspace $workspace, BusinessCard $businessCard)
    {
        $this->authorizeCardAccess($workspace);
        $this->ensureCardBelongsToWorkspace($workspace, $businessCard);

        $businessCard->load(['theme', 'socialLinks']);

        return view('business-cards.show', compact(
            'workspace',
            'businessCard'
        ));
    }

    /**
     * Show the edit card form.
     */
    public function edit(Workspace $workspace, BusinessCard $businessCard)
    {
        $this->authorizeCardAccess($workspace);
        $this->ensureCardBelongsToWorkspace($workspace, $businessCard);

        $themes = $this->availableThemes($workspace);

        return view('business-cards.edit', compact(
            'workspace',
            'businessCard',
            'themes'
        ));
    }

    /**
     * Update a business card.
     */
    public function update(
        Request $request,
        Workspace $workspace,
        BusinessCard $businessCard
    ) {
        $this->authorizeCardAccess($workspace);
        $this->ensureCardBelongsToWorkspace($workspace, $businessCard);

$validated = $this->validateCard($request, $workspace);

        $profilePhoto = $businessCard->profile_photo;
        $coverPhoto = $businessCard->cover_photo;

        if ($request->hasFile('profile_photo')) {
            if ($profilePhoto) {
                Storage::disk('public')->delete($profilePhoto);
            }

            $profilePhoto = $request->file('profile_photo')
                ->store('business-cards/profiles', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            if ($coverPhoto) {
                Storage::disk('public')->delete($coverPhoto);
            }

            $coverPhoto = $request->file('cover_photo')
                ->store('business-cards/covers', 'public');
        }

        $businessCard->update([
            ...$validated,
            'profile_photo' => $profilePhoto,
            'cover_photo' => $coverPhoto,
        ]);

        return redirect()
            ->route('business-cards.show', [$workspace, $businessCard])
            ->with('success', 'Business card updated successfully.');
    }

    /**
     * Publish a card.
     */
    public function publish(Workspace $workspace, BusinessCard $businessCard)
    {
        $this->authorizeCardAccess($workspace);
        $this->ensureCardBelongsToWorkspace($workspace, $businessCard);

        $businessCard->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return back()->with('success', 'Business card published successfully.');
    }

    /**
     * Return a published card to draft.
     */
    public function unpublish(Workspace $workspace, BusinessCard $businessCard)
    {
        $this->authorizeCardAccess($workspace);
        $this->ensureCardBelongsToWorkspace($workspace, $businessCard);

        $businessCard->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return back()->with('success', 'Business card unpublished successfully.');
    }

    /**
     * Set a card as the workspace's primary card.
     */
    public function setPrimary(Workspace $workspace, BusinessCard $businessCard)
    {
        $this->authorizeCardAccess($workspace);
        $this->ensureCardBelongsToWorkspace($workspace, $businessCard);

        DB::transaction(function () use ($workspace, $businessCard) {
            BusinessCard::where('workspace_id', $workspace->id)
                ->update(['is_primary' => false]);

            $businessCard->update(['is_primary' => true]);
        });

        return back()->with('success', 'Primary business card updated.');
    }

    /**
     * Delete a business card.
     */
    public function destroy(Workspace $workspace, BusinessCard $businessCard)
    {
        $this->authorizeCardAccess($workspace);
        $this->ensureCardBelongsToWorkspace($workspace, $businessCard);

        if ($businessCard->profile_photo) {
            Storage::disk('public')->delete($businessCard->profile_photo);
        }

        if ($businessCard->cover_photo) {
            Storage::disk('public')->delete($businessCard->cover_photo);
        }

        $businessCard->delete();

        return redirect()
            ->route('business-cards.index', $workspace)
            ->with('success', 'Business card deleted successfully.');
    }

    /**
     * Validate card information.
     */
  
private function validateCard(Request $request, Workspace $workspace): array
{
    $validated = $request->validate([
        'first_name' => ['required', 'string', 'max:100'],
        'last_name' => ['nullable', 'string', 'max:100'],
        'job_title' => ['nullable', 'string', 'max:150'],
        'company_name' => ['nullable', 'string', 'max:150'],
        'bio' => ['nullable', 'string', 'max:2000'],

        'email' => ['nullable', 'email', 'max:255'],
        'phone' => ['nullable', 'string', 'max:30'],
        'whatsapp' => ['nullable', 'string', 'max:30'],
        'website' => ['nullable', 'url', 'max:255'],

        'address' => ['nullable', 'string', 'max:255'],
        'city' => ['nullable', 'string', 'max:100'],
        'country' => ['nullable', 'string', 'max:100'],

        'card_theme_id' => [
            'nullable',
            'integer',
            Rule::exists('card_themes', 'id')->where(function ($query) use ($workspace) {
                $query->where('is_active', true)
                    ->where(function ($themeQuery) use ($workspace) {
                        $themeQuery->whereNull('workspace_id')
                            ->orWhere('workspace_id', $workspace->id);
                    });
            }),
        ],

        'visibility' => ['required', Rule::in(['public', 'private'])],

        'show_email' => ['nullable', 'boolean'],
        'show_phone' => ['nullable', 'boolean'],

        'profile_photo' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ],

        'cover_photo' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:8192',
        ],
    ]);

    // Unchecked HTML checkboxes are submitted as 0 by the hidden inputs.
    $validated['show_email'] = (bool) ($validated['show_email'] ?? false);
    $validated['show_phone'] = (bool) ($validated['show_phone'] ?? false);

    return $validated;
}

    /**
     * Get global themes and themes belonging to this workspace.
     */
    private function availableThemes(Workspace $workspace)
    {
        return CardTheme::where('is_active', true)
            ->where(function ($query) use ($workspace) {
                $query->whereNull('workspace_id')
                    ->orWhere('workspace_id', $workspace->id);
            })
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();
    }

    /**
     * Verify that the user can manage cards in this workspace.
     */
    private function authorizeCardAccess(Workspace $workspace): WorkspaceMember
    {
        $membership = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        abort_unless($membership, 403);

        abort_unless(
            $membership->hasPermission('manage_cards'),
            403,
            'You do not have permission to manage business cards in this workspace.'
        );

        return $membership;
    }

    /**
     * Prevent access to a card from another workspace.
     */
    private function ensureCardBelongsToWorkspace(
        Workspace $workspace,
        BusinessCard $businessCard
    ): void {
        abort_unless(
            $businessCard->workspace_id === $workspace->id,
            404
        );
    }
}
