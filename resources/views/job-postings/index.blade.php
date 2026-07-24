<x-layouts.app title="Job Postings">
<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <span class="font-bold text-teal-700">HireFlow</span>
    <a href="/dashboard" class="text-sm text-gray-600">← Dashboard</a>
</nav>

<div class="max-w-4xl mx-auto mt-10 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Job Postings</h1>
        @can('create-job')
            <a href="/jobs/create" class="bg-teal-600 text-white px-4 py-2 rounded">+ New Job</a>
        @endcan
    </div>

    <div class="bg-white rounded shadow divide-y">
        @forelse ($jobPostings as $job)
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h2 class="font-semibold">{{ $job->title }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ ucfirst($job->status) }} · {{ $job->applications_count }} applications
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="/jobs/{{ $job->id }}/pipeline" class="text-sm text-teal-600">View Pipeline</a>
                    <a href="/jobs/{{ $job->id }}/edit" class="text-sm text-gray-500">Edit</a>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-400">No job postings yet.</div>
        @endforelse
    </div>
</div>
</x-layouts.app>
