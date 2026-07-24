@php use Illuminate\Support\Facades\Storage; @endphp
<x-layouts.app title="Candidate Profile">
<div class="max-w-3xl mx-auto mt-10 px-4">
    <a href="/jobs/{{ $application->job_posting_id }}/pipeline" class="text-sm text-gray-500">← Back to Pipeline</a>

    <div class="bg-white rounded-lg shadow p-6 mt-4">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold">
                    {{ $application->candidate->first_name }} {{ $application->candidate->last_name }}
                </h1>
                <p class="text-gray-500">{{ $application->candidate->email }}</p>
                @if ($application->candidate->phone)
                    <p class="text-gray-500 text-sm">{{ $application->candidate->phone }}</p>
                @endif
            </div>
            <span class="bg-teal-100 text-teal-700 px-3 py-1 rounded text-sm font-medium">
                {{ $application->currentStage->name ?? 'No stage' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div>
                <span class="text-gray-400">Applied for</span>
                <p class="font-medium">{{ $application->jobPosting->title }}</p>
            </div>
            <div>
                <span class="text-gray-400">Applied on</span>
                <p class="font-medium">{{ $application->applied_at?->format('M j, Y') ?? '—' }}</p>
            </div>
            @if ($application->candidate->linkedin_url)
                <div>
                    <span class="text-gray-400">LinkedIn</span>
                    <p class="font-medium">
                        <a href="{{ $application->candidate->linkedin_url }}" target="_blank" class="text-teal-600 underline">
                            View profile
                        </a>
                    </p>
                </div>
            @endif
            @if ($application->candidate->resume_path)
                <div>
                    <span class="text-gray-400">Resume</span>
                    <p class="font-medium">
                        
                        <a href="{{ $application->candidate->resume_download_url }}" target="_blank" class="text-teal-600 underline">
                            Download resume
                        </a>
                    </p>
                </div>
            @endif
        </div>

        @if ($application->candidate->parsed_data)
            <div class="border-t pt-4 mb-6">
                <h2 class="font-semibold mb-2">Parsed from resume</h2>
                <div class="text-sm text-gray-600 space-y-1">
                    @if (!empty($application->candidate->parsed_data['detected_skills']))
                        <p><span class="text-gray-400">Skills detected:</span> {{ implode(', ', $application->candidate->parsed_data['detected_skills']) }}</p>
                    @endif
                    @if (!empty($application->candidate->parsed_data['error']))
                        <p class="text-red-500">{{ $application->candidate->parsed_data['error'] }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="flex gap-3 mt-4">
            <a href="/candidates/{{ $application->id }}/scorecard" class="bg-teal-600 text-white px-4 py-2 rounded text-sm">Submit Scorecard</a>
            <a href="/candidates/{{ $application->id }}/consensus" class="border px-4 py-2 rounded text-sm">View Consensus</a>
            <a href="/candidates/{{ $application->id }}/interviews" class="border px-4 py-2 rounded text-sm">Manage Interviews</a>
        </div>

        <div class="mt-8 border-t pt-4">
            @livewire('activity-feed', ['application' => $application])
        </div>
    </div>
</div>
</x-layouts.app>
