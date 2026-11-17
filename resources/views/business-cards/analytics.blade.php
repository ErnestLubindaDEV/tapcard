
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>
                <a
                    href="{{ route('business-cards.show', [
                        'workspace' => $workspace->id,
                        'businessCard' => $businessCard->id
                    ]) }}"
                    class="text-sm text-gray-500 hover:text-gray-800"
                >
                    &larr; Back to business card
                </a>

                <h1 class="text-2xl font-bold text-gray-900 mt-3">
                    Card Analytics
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Performance overview for
                    {{ $businessCard->first_name }}
                    {{ $businessCard->last_name }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">

                <form method="GET">
                    <select
                        name="period"
                        onchange="this.form.submit()"
                        class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="7" @selected($period === 7)>
                            Last 7 days
                        </option>

                        <option value="30" @selected($period === 30)>
                            Last 30 days
                        </option>

                        <option value="90" @selected($period === 90)>
                            Last 90 days
                        </option>
                    </select>
                </form>

                <a
                    href="{{ route('cards.public', ['slug' => $businessCard->slug]) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700"
                >
                    View Public Card
                </a>

            </div>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Total Views</p>
                    <span class="text-xl">👁️</span>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mt-3">
                    {{ number_format($totalViews) }}
                </h2>

                <p class="text-xs text-gray-400 mt-2">
                    Public card page visits
                </p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Unique Visitors</p>
                    <span class="text-xl">👤</span>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mt-3">
                    {{ number_format($uniqueVisitors) }}
                </h2>

                <p class="text-xs text-gray-400 mt-2">
                    Distinct anonymised IP hashes
                </p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">QR Scans</p>
                    <span class="text-xl">📱</span>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mt-3">
                    {{ number_format($qrScans) }}
                </h2>

                <p class="text-xs text-gray-400 mt-2">
                    Visits through card QR codes
                </p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Contact Saves</p>
                    <span class="text-xl">💾</span>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mt-3">
                    {{ number_format($contactSaves) }}
                </h2>

                <p class="text-xs text-gray-400 mt-2">
                    Contact downloads
                </p>
            </div>

        </div>

        <!-- ENGAGEMENT SUMMARY -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

            <div class="bg-indigo-600 rounded-xl p-6 text-white shadow-sm">
                <p class="text-sm text-indigo-100">
                    Total Engagement
                </p>

                <h2 class="text-3xl font-bold mt-2">
                    {{ number_format($totalEngagement) }}
                </h2>

                <p class="text-xs text-indigo-100 mt-2">
                    QR scans, contact saves and social link clicks
                </p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <p class="text-sm text-gray-500">
                    Engagement Rate
                </p>

                <h2 class="text-3xl font-bold text-gray-900 mt-2">
                    {{ number_format($engagementRate, 1) }}%
                </h2>

                <p class="text-xs text-gray-400 mt-2">
                    Total engagement events divided by card views
                </p>
            </div>

        </div>

        <!-- VIEWS CHART -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-8">

            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">
                    Card Views
                </h2>

                <p class="text-sm text-gray-500">
                    Daily views over the selected period
                </p>
            </div>

            <div class="relative h-72">
                <canvas id="viewsChart"></canvas>
            </div>

        </div>

        <!-- ENGAGEMENT BREAKDOWN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

            <!-- SOCIAL PLATFORM CLICKS -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">
                        Social Media Performance
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Clicks by social platform
                    </p>
                </div>

                <div class="p-6">

                    @forelse ($platformClicks as $platform)

                        <div class="mb-5 last:mb-0">

                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ $platform->platform ?: 'Unknown' }}
                                </span>

                                <span class="text-sm font-bold text-gray-900">
                                    {{ number_format($platform->total) }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div
                                    class="bg-indigo-600 h-2 rounded-full"
                                    style="width: {{ $linkClicks > 0 ? min(100, ($platform->total / $linkClicks) * 100) : 0 }}%"
                                ></div>
                            </div>

                        </div>

                    @empty

                        <div class="py-8 text-center">
                            <p class="text-sm text-gray-500">
                                No social media clicks recorded yet.
                            </p>
                        </div>

                    @endforelse

                </div>
            </div>

            <!-- TRAFFIC SOURCES -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">
                        Traffic Sources
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Where your card visits originated
                    </p>
                </div>

                <div class="p-6">

                    @forelse ($trafficSources as $source)

                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">

                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $source->source)) }}
                                </p>
                            </div>

                            <span class="text-sm font-bold text-gray-900">
                                {{ number_format($source->total) }}
                            </span>

                        </div>

                    @empty

                        <div class="py-8 text-center">
                            <p class="text-sm text-gray-500">
                                No traffic data available yet.
                            </p>
                        </div>

                    @endforelse

                </div>
            </div>

        </div>

        <!-- RECENT ACTIVITY -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">
                    Recent Activity
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Latest recorded interactions with your card
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Event
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Source
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Details
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Date
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($recentActivity as $activity)

                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-800">
                                        {{ ucwords(str_replace('_', ' ', $activity->event_type)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">
                                        {{ ucfirst($activity->event_source ?? 'Unknown') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">
                                        @if ($activity->event_type === 'link_click')
                                            {{ $activity->metadata['platform'] ?? 'Social link' }}
                                        @elseif ($activity->event_type === 'qr_scan')
                                            QR code scan
                                        @elseif ($activity->event_type === 'contact_save')
                                            Contact download
                                        @else
                                            Card page visit
                                        @endif
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">
                                        {{ $activity->occurred_at?->format('d M Y, H:i') }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No activity has been recorded yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartLabels = @json($chartLabels);
        const chartViews = @json($chartViews);

        const chartElement = document.getElementById('viewsChart');

        if (chartElement) {
            new Chart(chartElement, {
                type: 'line',

                data: {
                    labels: chartLabels,

                    datasets: [{
                        label: 'Card Views',
                        data: chartViews,

                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.10)',

                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,

                        pointRadius: 3,
                        pointHoverRadius: 6,
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },

                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    </script>

</x-app-layout>
