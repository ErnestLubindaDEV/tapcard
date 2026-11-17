
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <a
                href="{{ route('business-cards.show', [
                    'workspace' => $workspace->id,
                    'businessCard' => $businessCard->id
                ]) }}"
                class="text-sm text-gray-500 hover:text-gray-800"
            >
                &larr; Back to business card
            </a>

            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Social Media Links
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Manage the social profiles displayed on
                        {{ $businessCard->first_name }}'s TapCard.
                    </p>
                </div>

                <a
                    href="{{ route('cards.public', ['slug' => $businessCard->slug]) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700"
                >
                    Preview Public Card
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                <p class="font-semibold mb-2">
                    Please correct the following errors:
                </p>

                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ADD SOCIAL LINK -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-900 mb-5">
                        Add Social Profile
                    </h2>

                    <form
                        action="{{ route('business-cards.social-links.store', [
                            'workspace' => $workspace->id,
                            'businessCard' => $businessCard->id
                        ]) }}"
                        method="POST"
                        class="space-y-4"
                    >
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Platform
                            </label>

                            <select
                                name="platform"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Select platform</option>

                                @foreach ($platforms as $platform)
                                    <option
                                        value="{{ $platform }}"
                                        @selected(old('platform') === $platform)
                                    >
                                        {{ ucfirst($platform === 'twitter' ? 'X / Twitter' : $platform) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Display Label
                            </label>

                            <input
                                type="text"
                                name="label"
                                value="{{ old('label') }}"
                                placeholder="e.g. Follow me on Instagram"
                                maxlength="100"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Profile URL
                            </label>

                            <input
                                type="url"
                                name="url"
                                value="{{ old('url') }}"
                                placeholder="https://instagram.com/username"
                                required
                                maxlength="2048"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Display Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                value="{{ old('sort_order', 0) }}"
                                min="0"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            <p class="mt-1 text-xs text-gray-500">
                                Lower numbers appear first.
                            </p>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input
                                    type="checkbox"
                                    name="is_visible"
                                    value="1"
                                    checked
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                Display this link on my public card
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Add Social Link
                        </button>
                    </form>

                </div>
            </div>

            <!-- EXISTING SOCIAL LINKS -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">

                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">
                            Your Social Profiles
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $socialLinks->count() }} social link(s) added.
                        </p>
                    </div>

                    <div class="divide-y divide-gray-100">

                        @forelse ($socialLinks as $socialLink)

                            <div class="p-6">

                                <form
                                    action="{{ route('business-cards.social-links.update', [
                                        'workspace' => $workspace->id,
                                        'businessCard' => $businessCard->id,
                                        'socialLink' => $socialLink->id
                                    ]) }}"
                                    method="POST"
                                    class="space-y-4"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-gray-700">
                                                {{ strtoupper(substr($socialLink->platform, 0, 1)) }}
                                            </div>

                                            <div>
                                                <h3 class="font-semibold text-gray-900">
                                                    {{ ucfirst($socialLink->platform === 'twitter' ? 'X / Twitter' : $socialLink->platform) }}
                                                </h3>

                                                <p class="text-xs text-gray-500">
                                                    {{ $socialLink->is_visible ? 'Visible on public card' : 'Hidden from public card' }}
                                                </p>
                                            </div>
                                        </div>

                                        <span class="text-xs text-gray-400">
                                            Link #{{ $socialLink->id }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Platform
                                            </label>

                                            <select
                                                name="platform"
                                                required
                                                class="w-full rounded-lg border-gray-300"
                                            >
                                                @foreach ($platforms as $platform)
                                                    <option
                                                        value="{{ $platform }}"
                                                        @selected($socialLink->platform === $platform)
                                                    >
                                                        {{ ucfirst($platform === 'twitter' ? 'X / Twitter' : $platform) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Display Label
                                            </label>

                                            <input
                                                type="text"
                                                name="label"
                                                value="{{ $socialLink->label }}"
                                                maxlength="100"
                                                class="w-full rounded-lg border-gray-300"
                                            >
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Profile URL
                                            </label>

                                            <input
                                                type="url"
                                                name="url"
                                                value="{{ $socialLink->url }}"
                                                required
                                                maxlength="2048"
                                                class="w-full rounded-lg border-gray-300"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Display Order
                                            </label>

                                            <input
                                                type="number"
                                                name="sort_order"
                                                value="{{ $socialLink->sort_order }}"
                                                min="0"
                                                class="w-full rounded-lg border-gray-300"
                                            >
                                        </div>

                                        <div class="flex items-center pt-6">
                                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                                <input
                                                    type="checkbox"
                                                    name="is_visible"
                                                    value="1"
                                                    @checked($socialLink->is_visible)
                                                    class="rounded border-gray-300 text-indigo-600"
                                                >
                                                Visible on public card
                                            </label>
                                        </div>

                                    </div>

                                    <div class="flex flex-wrap gap-3">

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700"
                                        >
                                            Save Changes
                                        </button>

                                        <a
                                            href="{{ $socialLink->url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                        >
                                            Visit Profile
                                        </a>

                                    </div>
                                </form>

                                <form
                                    action="{{ route('business-cards.social-links.destroy', [
                                        'workspace' => $workspace->id,
                                        'businessCard' => $businessCard->id,
                                        'socialLink' => $socialLink->id
                                    ]) }}"
                                    method="POST"
                                    class="mt-3"
                                    onsubmit="return confirm('Are you sure you want to remove this social media link?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-sm font-medium text-red-600 hover:text-red-800"
                                    >
                                        Remove Link
                                    </button>
                                </form>

                            </div>

                        @empty

                            <div class="p-10 text-center">
                                <div class="text-4xl mb-3">🔗</div>

                                <h3 class="font-semibold text-gray-900">
                                    No social profiles yet
                                </h3>

                                <p class="mt-2 text-sm text-gray-500">
                                    Add your first social media profile using the form.
                                </p>
                            </div>

                        @endforelse

                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
