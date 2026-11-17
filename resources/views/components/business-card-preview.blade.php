
<div class="sticky top-6">
    <div class="mb-3">
        <h3 class="text-lg font-semibold text-gray-900">Live Preview</h3>
        <p class="text-sm text-gray-500">
            Your card updates as you edit the form.
        </p>
    </div>

    <div
        class="overflow-hidden rounded-2xl border border-gray-200 shadow-xl"
        :style="{
            backgroundColor: selectedTheme.colors.background || '#F8FAFC',
            borderRadius: selectedTheme.settings.border_radius || '16px',
            fontFamily: selectedTheme.settings.font || 'DM Sans, sans-serif'
        }"
    >
        <!-- Cover image -->
        <div
            class="relative h-36 overflow-hidden"
            :style="{ backgroundColor: selectedTheme.colors.primary || '#2563EB' }"
        >
            <template x-if="coverPreview">
                <img
                    :src="coverPreview"
                    alt="Card cover"
                    class="h-full w-full object-cover"
                >
            </template>

            <div
                x-show="!coverPreview"
                class="absolute inset-0 opacity-20"
                style="background-image: radial-gradient(circle at 20% 20%, white 1px, transparent 1px); background-size: 18px 18px;"
            ></div>

            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>

        <!-- Card content -->
        <div class="relative px-5 pb-6">
            <!-- Profile image -->
            <div class="-mt-14 mb-4">
                <template x-if="profilePreview">
                    <img
                        :src="profilePreview"
                        alt="Profile photo"
                        class="h-28 w-28 rounded-2xl border-4 border-white object-cover shadow-md"
                    >
                </template>

                <template x-if="!profilePreview">
                    <div
                        class="flex h-28 w-28 items-center justify-center rounded-2xl border-4 border-white text-3xl font-bold shadow-md"
                        :style="{
                            backgroundColor: selectedTheme.colors.surface || '#FFFFFF',
                            color: selectedTheme.colors.primary || '#2563EB'
                        }"
                    >
                        <span x-text="initials"></span>
                    </div>
                </template>
            </div>

            <!-- Name -->
            <h2
                class="break-words text-2xl font-bold"
                :style="{ color: selectedTheme.colors.text || '#172554' }"
                x-text="fullName"
            ></h2>

            <!-- Job title -->
            <p
                class="mt-1 text-sm font-medium"
                :style="{ color: selectedTheme.colors.primary || '#2563EB' }"
                x-text="jobTitle || 'Your professional title'"
            ></p>

            <!-- Company -->
            <p
                x-show="company"
                class="mt-1 text-sm"
                :style="{ color: selectedTheme.colors.muted || '#64748B' }"
                x-text="company"
            ></p>

            <!-- Biography -->
            <p
                class="mt-5 whitespace-pre-line break-words text-sm leading-6"
                :style="{ color: selectedTheme.colors.text || '#172554' }"
                x-text="bio || 'Your professional biography will appear here. Tell people a little about yourself and what you do.'"
            ></p>

            <!-- Contact information -->
            <div class="mt-6 space-y-3">
                <div
                    x-show="showEmail && email"
                    class="flex items-center gap-3 break-all text-sm"
                    :style="{ color: selectedTheme.colors.text || '#172554' }"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                        :style="{
                            backgroundColor: selectedTheme.colors.primary || '#2563EB',
                            color: '#FFFFFF'
                        }"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="m3 7 9 6 9-6"></path>
                        </svg>
                    </span>
                    <span x-text="email"></span>
                </div>

                <div
                    x-show="showPhone && phone"
                    class="flex items-center gap-3 text-sm"
                    :style="{ color: selectedTheme.colors.text || '#172554' }"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                        :style="{
                            backgroundColor: selectedTheme.colors.primary || '#2563EB',
                            color: '#FFFFFF'
                        }"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L9.06 10.9a16 16 0 0 0 4.04 4.04l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.58 2.8.7a2 2 0 0 1 1.72 2z"></path>
                        </svg>
                    </span>
                    <span x-text="phone"></span>
                </div>

                <div
                    x-show="website"
                    class="flex items-center gap-3 break-all text-sm"
                    :style="{ color: selectedTheme.colors.text || '#172554' }"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                        :style="{
                            backgroundColor: selectedTheme.colors.primary || '#2563EB',
                            color: '#FFFFFF'
                        }"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M2 12h20"></path>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </span>
                    <span x-text="website"></span>
                </div>
            </div>

            <!-- Theme label -->
            <div class="mt-7 border-t pt-4"
                 :style="{ borderColor: selectedTheme.colors.muted || '#64748B' }">
                <div class="flex items-center justify-between">
                    <span
                        class="text-xs font-medium"
                        :style="{ color: selectedTheme.colors.muted || '#64748B' }"
                    >
                        Powered by TapCard
                    </span>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-semibold"
                        :style="{
                            backgroundColor: selectedTheme.colors.primary || '#2563EB',
                            color: '#FFFFFF'
                        }"
                        x-text="selectedTheme.name || 'Default Theme'"
                    ></span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 rounded-lg border border-blue-100 bg-blue-50 p-3">
        <p class="text-xs leading-5 text-blue-800">
            This is a preview of your digital business card. The final public
            card will use the selected theme and saved information.
        </p>
    </div>
</div>
