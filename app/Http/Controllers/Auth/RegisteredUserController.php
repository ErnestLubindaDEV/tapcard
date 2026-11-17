<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        // Create the user and personal workspace atomically.
        $user = DB::transaction(function () use ($request) {

            // Create the user account.
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Generate a unique workspace slug.
            $slug = Str::slug($user->name)
                . '-' . Str::lower(Str::random(8));

            // Create the user's personal workspace.
            $workspace = Workspace::create([
                'name' => $user->name . "'s Workspace",
                'slug' => $slug,
                'type' => 'personal',
                'email' => $user->email,
                'status' => 'active',
                'timezone' => 'Africa/Lusaka',
                'created_by' => $user->id,
            ]);

            // Add the user as the workspace owner.
            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
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

            return $user;
        });

        // Fire the registration event after successful creation.
        event(new Registered($user));

        // Log the user in.
        Auth::login($user);

        // Regenerate the session to prevent session fixation.
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
