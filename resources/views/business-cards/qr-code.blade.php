
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Business Card QR Code
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Share this code so people can open your digital business card.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-6 py-5 text-center">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $businessCard->full_name }}
                    </h3>

                    @if ($businessCard->job_title)
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $businessCard->job_title }}
                        </p>
                    @endif

                    @if ($businessCard->company_name)
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $businessCard->company_name }}
                        </p>
                    @endif
                </div>

                <div class="px-6 py-8 text-center">

                    <div class="mx-auto flex max-w-sm items-center justify-center rounded-2xl border border-gray-100 bg-white p-5">
                        <img
                            src="{{ $qrCode }}"
                            alt="QR code for {{ $businessCard->full_name }}"
                            class="h-auto w-full max-w-xs"
                        >
                    </div>

                    <h4 class="mt-6 text-lg font-semibold text-gray-900">
                        Scan to connect
                    </h4>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        Anyone who scans this QR code will be taken directly
                        to your public TapCard profile.
                    </p>

                    <div class="mx-auto mt-6 max-w-lg rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Your public card URL
                        </p>

                        <div class="flex flex-col items-stretch gap-2 sm:flex-row">
                            <input
                                id="publicCardUrl"
                                type="text"
                                readonly
                                value="{{ $publicUrl }}"
                                class="min-w-0 flex-1 rounded-lg border-gray-300 bg-white text-sm text-gray-700"
                            >

                            <button
                                type="button"
                                onclick="copyCardUrl()"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700"
                            >
                                Copy link
                            </button>
                        </div>

                        <p id="copyMessage" class="mt-2 hidden text-xs text-green-600">
                            Link copied successfully.
                        </p>
                    </div>

                    <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">

                        <a
                            href="{{ route('cards.qr.download', ['slug' => $businessCard->slug]) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 10l5 5 5-5M12 15V3"/>
                            </svg>
                            Download QR Code
                        </a>

                        <a
                            href="{{ $publicUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            View Public Card
                        </a>

                    </div>

                    <p class="mt-5 text-xs text-gray-400">
                        SVG format · Scalable · Suitable for print and digital sharing
                    </p>
                </div>
            </div>

            <div class="mt-5">
                <a
                    href="{{ route('business-cards.show', [
                        'workspace' => $businessCard->workspace_id,
                        'businessCard' => $businessCard->id
                    ]) }}"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                >
                    &larr; Back to business card
                </a>
            </div>

        </div>
    </div>

    <script>
        function copyCardUrl() {
            const input = document.getElementById('publicCardUrl');
            const message = document.getElementById('copyMessage');

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(input.value).then(() => {
                    message.classList.remove('hidden');
                }).catch(() => {
                    fallbackCopy(input, message);
                });
            } else {
                fallbackCopy(input, message);
            }
        }

        function fallbackCopy(input, message) {
            input.select();
            input.setSelectionRange(0, 99999);

            const copied = document.execCommand('copy');

            if (copied) {
                message.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
