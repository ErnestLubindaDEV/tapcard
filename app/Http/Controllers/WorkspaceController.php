<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WorkspaceController extends Controller
{
    /**
     * Display workspaces belonging to the authenticated user.
     */
    public function index()
    {
        $workspaces = Auth::user()
            ->activeWorkspaces()
            ->withCount([
                'members as active_members_count' => function ($query) {
                    $query->where('workspace_members.status', 'active');
                },
                'businessCards',
            ])
            ->latest('workspaces.created_at')
            ->get();

        return view('workspaces.index', compact('workspaces'));
    }

    /**
     * Show the business workspace creation form.
     */
    public function create()
    {
        return view('workspaces.create');
    }

    /**
     * Create a business workspace and assign its creator as owner.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'timezone' => [
                'required',
                'timezone',
            ],
        ]);

        $workspace = DB::transaction(function () use ($validated) {
            $baseSlug = Str::slug($validated['name']);

            if ($baseSlug === '') {
                $baseSlug = 'workspace';
            }

            $slug = $baseSlug . '-' . Str::lower(Str::random(8));

            $workspace = Workspace::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'type' => 'business',
                'description' => $validated['description'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'website' => $validated['website'] ?? null,
                'country' => $validated['country'] ?? null,
                'timezone' => $validated['timezone'],
                'status' => 'active',
                'created_by' => Auth::id(),
            ]);

            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => Auth::id(),
                'role' => 'owner',
                'permissions' => [
                    'manage_workspace',
                    'manage_members',
                    'manage_cards',
                    'manage_themes',
                    'view_analytics',
                ],
                'status' => 'active',
                'joined_at' => now(),
            ]);

            return $workspace;
        });

        return redirect()
            ->route('workspaces.index')
            ->with('success', 'Your business workspace has been created successfully.');
    }

    /**
     * Display one workspace.
     * Users can only view workspaces where they have active membership.
     */
    public function show(Workspace $workspace)
    {
        $this->authorizeWorkspaceAccess($workspace);

        $workspace->loadCount([
            'businessCards',
            'members',
        ]);

        return view('workspaces.show', compact('workspace'));
    }

    /**
     * Show the workspace editing form.
     */
    public function edit(Workspace $workspace)
    {
        $this->authorizeWorkspaceManagement($workspace);

        return view('workspaces.edit', compact('workspace'));
    }

    /**
     * Update workspace details.
     */
    public function update(Request $request, Workspace $workspace)
    {
        $this->authorizeWorkspaceManagement($workspace);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'url', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'timezone' => ['required', 'timezone'],
        ]);

        $workspace->update($validated);

        return redirect()
            ->route('workspaces.show', $workspace)
            ->with('success', 'Workspace details updated successfully.');
    }

    /**
     * Delete a business workspace.
     *
     * Only its owner can delete it. Personal workspaces cannot be deleted here.
     */
    public function destroy(Workspace $workspace)
    {
        $this->authorizeWorkspaceAccess($workspace);

        $membership = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        abort_unless($membership && $membership->role === 'owner', 403);

        abort_if(
            $workspace->type !== 'business',
            403,
            'Personal workspaces cannot be deleted here.'
        );

        $workspace->delete();

        return redirect()
            ->route('workspaces.index')
            ->with('success', 'Business workspace deleted successfully.');
    }

    /**
     * Ensure the current user is an active member of this workspace.
     */
    private function authorizeWorkspaceAccess(Workspace $workspace): void
    {
        $isMember = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->exists();

        abort_unless($isMember, 403, 'You do not have access to this workspace.');
    }

    /**
     * Ensure the current user can manage workspace details.
     */
    private function authorizeWorkspaceManagement(Workspace $workspace): void
    {
        $membership = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        abort_unless(
            $membership && in_array($membership->role, ['owner', 'admin'], true),
            403,
            'You do not have permission to manage this workspace.'
        );
    }
}
