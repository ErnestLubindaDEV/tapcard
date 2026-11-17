
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Create Business Card
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Add the details that people will see on your digital profile.
            </p>
        </div>
    </x-slot>

    @php
        $themeData = $themes->map(fn ($theme) => [
            'id' => (string) $theme->id,
            'name' => $theme->name,
            'colors' => $theme->colors ?? [],
            'settings' => $theme->settings ?? [],
        ])->values();

        $initialData = [
            'card_theme_id' => (string) old('card_theme_id', ''),
            'first_name' => old('first_name', ''),
            'last_name' => old('last_name', ''),
            'job_title' => old('job_title', ''),
            'company_name' => old('company_name', ''),
            'bio' => old('bio', ''),
            'email' => old('email', ''),
            'phone' => old('phone', ''),
            'website' => old('website', ''),
            'show_email' => (bool) old('show_email', true),
            'show_phone' => (bool) old('show_phone', true),
            'profile_photo_url' => null,
            'cover_photo_url' => null,
        ];
    @endphp

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <strong>Please correct the following errors:</strong>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('business-cards.store', ['workspace' => $workspace->id]) }}"
                enctype="multipart/form-data"
                x-data="tapCardBuilder(
                    @js($themeData),
                    @js($initialData)
                )"
                class="space-y-6"
            >
                @csrf

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

                    <!-- FORM FIELDS -->
                    <div class="space-y-6 lg:col-span-3">

                        <!-- PERSONAL INFORMATION -->
                        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Personal Information
                            </h3>
                            <p class="mb-6 mt-1 text-sm text-gray-500">
                                Tell people who you are.
                            </p>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="first_name" class="mb-2 block text-sm font-medium text-gray-700">
                                        First Name *
                                    </label>
                                    <input
                                        id="first_name"
                                        type="text"
                                        name="first_name"
                                        x-model="firstName"
                                        required
                                        maxlength="100"
                                        placeholder="Enter first name"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div>
                                    <label for="last_name" class="mb-2 block text-sm font-medium text-gray-700">
                                        Last Name
                                    </label>
                                    <input
                                        id="last_name"
                                        type="text"
                                        name="last_name"
                                        x-model="lastName"
                                        maxlength="100"
                                        placeholder="Enter last name"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div>
                                    <label for="job_title" class="mb-2 block text-sm font-medium text-gray-700">
                                        Job Title
                                    </label>
                                    <input
                                        id="job_title"
                                        type="text"
                                        name="job_title"
                                        x-model="jobTitle"
                                        placeholder="e.g. Software Developer"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div>
                                    <label for="company_name" class="mb-2 block text-sm font-medium text-gray-700">
                                        Company / Organisation
                                    </label>
                                    <input
                                        id="company_name"
                                        type="text"
                                        name="company_name"
                                        x-model="company"
                                        placeholder="Company name"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                            </div>

                            <div class="mt-5">
                                <label for="bio" class="mb-2 block text-sm font-medium text-gray-700">
                                    Professional Biography
                                </label>
                                <textarea
                                    id="bio"
                                    name="bio"
                                    x-model="bio"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Write a short professional biography..."
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>
                            </div>
                        </section>

                        <!-- CONTACT INFORMATION -->
                        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Contact Information
                            </h3>
                            <p class="mb-6 mt-1 text-sm text-gray-500">
                                Add the contact details people can use to reach you.
                            </p>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                                        Email Address
                                    </label>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        x-model="email"
                                        placeholder="name@example.com"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div>
                                    <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                                        Phone Number
                                    </label>
                                    <input
                                        id="phone"
                                        type="text"
                                        name="phone"
                                        x-model="phone"
                                        placeholder="+260..."
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div>
                                    <label for="whatsapp" class="mb-2 block text-sm font-medium text-gray-700">
                                        WhatsApp Number
                                    </label>
                                    <input
                                        id="whatsapp"
                                        type="text"
                                        name="whatsapp"
                                        value="{{ old('whatsapp') }}"
                                        placeholder="+260..."
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div>
                                    <label for="website" class="mb-2 block text-sm font-medium text-gray-700">
                                        Website
                                    </label>
                                    <input
                                        id="website"
                                        type="url"
                                        name="website"
                                        x-model="website"
                                        placeholder="https://example.com"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                            </div>
                        </section>

                        <!-- LOCATION -->
                        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="mb-6 text-lg font-semibold text-gray-900">
                                Location
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <label for="address" class="mb-2 block text-sm font-medium text-gray-700">
                                        Physical Address
                                    </label>
                                    <input
                                        id="address"
                                        type="text"
                                        name="address"
                                        value="{{ old('address') }}"
                                        placeholder="Street or business address"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="city" class="mb-2 block text-sm font-medium text-gray-700">
                                            City
                                        </label>
                                        <input
                                            id="city"
                                            type="text"
                                            name="city"
                                            value="{{ old('city') }}"
                                            placeholder="e.g. Lusaka"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>

                                    <div>
                                        <label for="country" class="mb-2 block text-sm font-medium text-gray-700">
                                            Country
                                        </label>
                                        <input
                                            id="country"
                                            type="text"
                                            name="country"
                                            value="{{ old('country', 'Zambia') }}"
                                            placeholder="e.g. Zambia"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- CARD APPEARANCE -->
                        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Card Appearance
                            </h3>
                            <p class="mb-6 mt-1 text-sm text-gray-500">
                                Personalise your digital business card.
                            </p>

                            <div class="mb-6">
                                <label for="card_theme_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    Card Theme
                                </label>

                                <select
                                    id="card_theme_id"
                                    name="card_theme_id"
                                    x-model="themeId"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Default Theme</option>
                                    @foreach ($themes as $theme)
                                        <option value="{{ $theme->id }}">
                                            {{ $theme->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="profile_photo" class="mb-2 block text-sm font-medium text-gray-700">
                                        Profile Photo
                                    </label>
                                    <input
                                        id="profile_photo"
                                        type="file"
                                        name="profile_photo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        @change="readImage($event, 'profile')"
                                        class="w-full text-sm text-gray-600"
                                    >
                                    <p class="mt-2 text-xs text-gray-400">
                                        JPG, PNG or WebP. Maximum 5 MB.
                                    </p>
                                </div>

                                <div>
                                    <label for="cover_photo" class="mb-2 block text-sm font-medium text-gray-700">
                                        Cover Photo
                                    </label>
                                    <input
                                        id="cover_photo"
                                        type="file"
                                        name="cover_photo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        @change="readImage($event, 'cover')"
                                        class="w-full text-sm text-gray-600"
                                    >
                                    <p class="mt-2 text-xs text-gray-400">
                                        JPG, PNG or WebP. Maximum 8 MB.
                                    </p>
                                </div>
                            </div>
                        </section>

                        <!-- PRIVACY SETTINGS -->
                        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="mb-6 text-lg font-semibold text-gray-900">
                                Privacy and Visibility
                            </h3>

                            <div class="mb-6">
                                <label for="visibility" class="mb-2 block text-sm font-medium text-gray-700">
                                    Card Visibility
                                </label>
                                <select
                                    id="visibility"
                                    name="visibility"
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="public" @selected(old('visibility', 'public') === 'public')>
                                        Public
                                    </option>
                                    <option value="private" @selected(old('visibility') === 'private')>
                                        Private
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-4">
                                <label class="flex items-center gap-3">
                                    <input type="hidden" name="show_email" value="0">
                                    <input
                                        type="checkbox"
                                        name="show_email"
                                        value="1"
                                        x-model="showEmail"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span class="text-sm text-gray-700">
                                        Display email address on my card
                                    </span>
                                </label>

                                <label class="flex items-center gap-3">
                                    <input type="hidden" name="show_phone" value="0">
                                    <input
                                        type="checkbox"
                                        name="show_phone"
                                        value="1"
                                        x-model="showPhone"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span class="text-sm text-gray-700">
                                        Display phone number on my card
                                    </span>
                                </label>
                            </div>
                        </section>
                    </div>

                    <!-- LIVE PREVIEW -->
                    <div class="lg:col-span-2">
                        <x-business-card-preview />
                    </div>
                </div>

                <!-- FORM ACTIONS -->
                <div class="flex flex-col justify-end gap-3 border-t border-gray-200 pt-6 sm:flex-row">
                    <a
                        href="{{ route('business-cards.index', ['workspace' => $workspace->id]) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-7 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                    >
                        Create Business Card
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tapCardBuilder(themes, initial = {}) {
            return {
                themes: themes || [],

                themeId: initial.card_theme_id ?? '',
                firstName: initial.first_name ?? '',
                lastName: initial.last_name ?? '',
                jobTitle: initial.job_title ?? '',
                company: initial.company_name ?? '',
                bio: initial.bio ?? '',
                email: initial.email ?? '',
                phone: initial.phone ?? '',
                website: initial.website ?? '',

                showEmail: initial.show_email ?? true,
                showPhone: initial.show_phone ?? true,

                profilePreview: initial.profile_photo_url ?? null,
                coverPreview: initial.cover_photo_url ?? null,

                get selectedTheme() {
                    return this.themes.find(
                        theme => String(theme.id) === String(this.themeId)
                    ) || this.themes.find(
                        theme => theme.name === 'Executive'
                    ) || {
                        name: 'Default Theme',
                        colors: {
                            background: '#F8FAFC',
                            surface: '#FFFFFF',
                            primary: '#2563EB',
                            secondary: '#0F172A',
                            text: '#172554',
                            muted: '#64748B'
                        },
                        settings: {
                            font: 'DM Sans, sans-serif',
                            border_radius: '16px'
                        }
                    };
                },

                get fullName() {
                    return `${this.firstName} ${this.lastName}`.trim() || 'Your Name';
                },

                get initials() {
                    return (
                        (this.firstName.charAt(0) || 'T') +
                        (this.lastName.charAt(0) || 'C')
                    ).toUpperCase();
                },

                readImage(event, type) {
                    const file = event.target.files[0];

                    if (!file) {
                        return;
                    }

                    const allowedTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ];

                    if (!allowedTypes.includes(file.type)) {
                        alert('Please select a JPG, PNG or WebP image.');
                        event.target.value = '';
                        return;
                    }

                    const maxSize = type === 'profile'
                        ? 5 * 1024 * 1024
                        : 8 * 1024 * 1024;

                    if (file.size > maxSize) {
                        alert(
                            type === 'profile'
                                ? 'Profile photo must not exceed 5 MB.'
                                : 'Cover photo must not exceed 8 MB.'
                        );

                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = (e) => {
                        if (type === 'profile') {
                            this.profilePreview = e.target.result;
                        } else {
                            this.coverPreview = e.target.result;
                        }
                    };

                    reader.readAsDataURL(file);
                }
            };
        }
    </script>
</x-app-layout>
