
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Workspace Settings
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Update {{ $workspace->name }}.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('workspaces.update', $workspace) }}"
                  method="POST"
                  class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')

                <div>
                    <h3 class="font-semibold text-gray-900">
                        Organisation details
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Keep your workspace information up to date.
                    </p>
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                        Organisation name *
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $workspace->name) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $workspace->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                            Business email
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $workspace->email) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                            Business phone
                        </label>
                        <input type="text" name="phone" id="phone"
                               value="{{ old('phone', $workspace->phone) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="website" class="mb-2 block text-sm font-medium text-gray-700">
                        Website
                    </label>
                    <input type="url" name="website" id="website"
                           value="{{ old('website', $workspace->website) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="country" class="mb-2 block text-sm font-medium text-gray-700">
                            Country
                        </label>
                        <input type="text" name="country" id="country"
                               value="{{ old('country', $workspace->country) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="timezone" class="mb-2 block text-sm font-medium text-gray-700">
                            Timezone *
                        </label>
                        <select name="timezone" id="timezone" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Africa/Lusaka', 'Africa/Johannesburg', 'Africa/Nairobi', 'Europe/London', 'America/New_York'] as $timezone)
                                <option value="{{ $timezone }}"
                                    {{ old('timezone', $workspace->timezone) === $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                        @error('timezone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse justify-between gap-3 border-t border-gray-100 pt-5 sm:flex-row">
                    <div>
                        <button type="button"
                                onclick="if(confirm('Delete this business workspace? This action cannot be undone easily.')) document.getElementById('delete-workspace-form').submit();"
                                class="rounded-lg border border-red-200 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">
                            Delete workspace
                        </button>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('workspaces.show', $workspace) }}"
                           class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-workspace-form"
                  action="{{ route('workspaces.destroy', $workspace) }}"
                  method="POST"
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
</x-app-layout>
