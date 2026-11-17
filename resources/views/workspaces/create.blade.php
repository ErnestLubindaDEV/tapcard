
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Create Business Workspace
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Set up a workspace for your company or organisation.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <form action="{{ route('workspaces.store') }}" method="POST"
                  class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                @csrf

                <div>
                    <h3 class="text-base font-semibold text-gray-900">
                        Organisation details
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Enter the basic information about your organisation.
                    </p>
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                        Organisation name *
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Rideve Media"
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
                    <textarea name="description" id="description" rows="3"
                              placeholder="Tell us about your organisation..."
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>

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
                               value="{{ old('email') }}"
                               placeholder="info@company.com"
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
                               value="{{ old('phone') }}"
                               placeholder="+260..."
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
                           value="{{ old('website') }}"
                           placeholder="https://www.company.com"
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
                               value="{{ old('country', 'Zambia') }}"
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
                            <option value="Africa/Lusaka"
                                {{ old('timezone', 'Africa/Lusaka') === 'Africa/Lusaka' ? 'selected' : '' }}>
                                Africa/Lusaka
                            </option>
                            <option value="Africa/Johannesburg"
                                {{ old('timezone') === 'Africa/Johannesburg' ? 'selected' : '' }}>
                                Africa/Johannesburg
                            </option>
                            <option value="Africa/Nairobi"
                                {{ old('timezone') === 'Africa/Nairobi' ? 'selected' : '' }}>
                                Africa/Nairobi
                            </option>
                            <option value="Europe/London"
                                {{ old('timezone') === 'Europe/London' ? 'selected' : '' }}>
                                Europe/London
                            </option>
                            <option value="America/New_York"
                                {{ old('timezone') === 'America/New_York' ? 'selected' : '' }}>
                                America/New_York
                            </option>
                        </select>

                        @error('timezone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse justify-end gap-3 border-t border-gray-100 pt-5 sm:flex-row">
                    <a href="{{ route('workspaces.index') }}"
                       class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>

                    <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Create Business Workspace
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
