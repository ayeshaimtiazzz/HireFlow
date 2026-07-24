<div class="max-w-3xl mx-auto mt-10 px-4">
    <h1 class="text-3xl font-bold mb-2">Careers at {{ $tenant->name }}</h1>
    <p class="text-gray-500 mb-8">Open positions</p>

    <div class="space-y-4">
        @forelse ($jobs as $job)
            <a href="/jobs/{{ $job->id }}/apply" class="block bg-white p-6 rounded-lg shadow hover:shadow-md">
                <h2 class="text-lg font-semibold text-teal-700">{{ $job->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ ucfirst($job->location_type) }} · {{ $job->location }}
                    @if ($job->salary_min && $job->salary_max)
                        · PKR {{ number_format($job->salary_min) }}–{{ number_format($job->salary_max) }}
                    @endif
                </p>
            </a>
        @empty
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-400">
                No open positions right now. Check back soon!
            </div>
        @endforelse
    </div>
</div>
