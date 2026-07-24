<!DOCTYPE html>
<html>
<head>
    <title>Book an interview</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen p-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
        <h1 class="text-xl font-bold mb-1">Book your interview</h1>
        <p class="text-gray-500 text-sm mb-6">
            for {{ $application->jobPosting->title }} at {{ $application->jobPosting->tenant->name ?? '' }}
        </p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-3 rounded mb-4 text-sm">{{ $errors->first() }}</div>
        @endif

        <div class="space-y-2">
            @forelse ($slots as $slot)
                <form method="GET" action="{{ URL::temporarySignedRoute('interview.book.confirm', now()->addDays(7), ['tenant' => request()->route('tenant'), 'slot' => $slot->id, 'application' => $application->id]) }}">
                    <button type="submit" class="w-full text-left border rounded p-3 hover:bg-teal-50 hover:border-teal-400">
                        {{ $slot->starts_at->format('l, M j \a\t g:i A') }} ({{ $slot->duration_minutes }} min)
                    </button>
                </form>
            @empty
                <p class="text-gray-400 text-sm">No slots available right now. Please check back later.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
