
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Workspace Members
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Manage access and roles for {{ $workspace->name }}.
                </p>
            </div>

            <a href="{{ route('workspaces.show', $workspace) }}"
               class="text-sm text-indigo-600 hover:text-indigo-800">
                &larr; Back to workspace
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Feedback --}}
            @if(session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Summary --}}
            <div class="bg-white shadow-sm rounded-xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Team members
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $members->count() }} registered member(s)
                        </p>
                    </div>

                    <div class="flex gap-3 text-sm">
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                            {{ $members->where('status', 'active')->count() }} Active
                        </span>

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                            {{ $members->where('status', 'suspended')->count() }} Suspended
                        </span>
                    </div>
                </div>
            </div>

            {{-- Members table --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Member
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Role
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Joined
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($members as $member)
                                <tr>
                                    {{-- Member --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold">
                                                {{ strtoupper(substr($member->user->name ?? 'U', 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $member->user->name ?? 'Unknown user' }}

                                                    @if($member->user_id === auth()->id())
                                                        <span class="text-xs text-gray-400">(You)</span>
                                                    @endif
                                                </div>

                                                <div class="text-sm text-gray-500">
                                                    {{ $member->user->email ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Role --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->user_id === auth()->id())
                                            <span class="text-sm font-medium text-gray-700 capitalize">
                                                {{ $member->role }}
                                            </span>
                                        @else
                                            <form method="POST"
                                                  action="{{ route('workspaces.members.role.update', [$workspace, $member]) }}"
                                                  class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')

                                                <select name="role"
                                                        class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                                        onchange="this.form.submit()">
                                                    <option value="owner" @selected($member->role === 'owner')>
                                                        Owner
                                                    </option>
                                                    <option value="admin" @selected($member->role === 'admin')>
                                                        Admin
                                                    </option>
                                                    <option value="member" @selected($member->role === 'member')>
                                                        Member
                                                    </option>
                                                </select>
                                            </form>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->status === 'active')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                Suspended
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Joined --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->joined_at?->format('d M Y') ?? $member->created_at?->format('d M Y') ?? '—' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($member->user_id !== auth()->id())
                                            <div class="flex justify-end items-center gap-3">

                                                {{-- Suspend / Reactivate --}}
                                                <form method="POST"
                                                      action="{{ route('workspaces.members.status.update', [$workspace, $member]) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden"
                                                           name="status"
                                                           value="{{ $member->status === 'active' ? 'suspended' : 'active' }}">

                                                    <button type="submit"
                                                            class="text-sm font-medium {{ $member->status === 'active' ? 'text-amber-600 hover:text-amber-800' : 'text-green-600 hover:text-green-800' }}"
                                                            onclick="return confirm('Are you sure you want to {{ $member->status === 'active' ? 'suspend' : 'reactivate' }} this member?')">
                                                        {{ $member->status === 'active' ? 'Suspend' : 'Reactivate' }}
                                                    </button>
                                                </form>

                                                {{-- Remove --}}
                                                <form method="POST"
                                                      action="{{ route('workspaces.members.destroy', [$workspace, $member]) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="text-sm font-medium text-red-600 hover:text-red-800"
                                                            onclick="return confirm('Are you sure you want to remove this member from the workspace?')">
                                                        Remove
                                                    </button>
                                                </form>

                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">
                                                Your account
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No members found in this workspace.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Role information --}}
            <div class="bg-white shadow-sm rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 mb-4">
                    Understanding member roles
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <h4 class="font-medium text-gray-900">Owner</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            Full control over the workspace, its settings, members, cards and analytics.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-900">Admin</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            Manages members, business cards, themes and analytics, but cannot manage workspace settings.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-900">Member</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            Can manage business cards within the workspace.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
