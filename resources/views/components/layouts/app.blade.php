<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'HireFlow' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen antialiased">
    @auth
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <span class="font-bold text-teal-700">HireFlow</span>
                <a href="/dashboard" class="text-sm text-gray-600 hover:text-teal-600">Dashboard</a>
                <a href="/jobs" class="text-sm text-gray-600 hover:text-teal-600">Job Postings</a>
                @if (auth()->user()->hasRole('company_admin'))
                    <a href="/team" class="text-sm text-gray-600 hover:text-teal-600">Team</a>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="text-sm text-red-600">Log out</button>
                </form>
            </div>
        </nav>

        <div id="live-notification" class="hidden fixed top-4 right-4 bg-teal-600 text-white px-4 py-3 rounded shadow-lg z-50"></div>
        <script>
            document.addEventListener('livewire:navigated', () => {
                if (window.Echo && {{ auth()->user()->tenant_id ?? 'null' }}) {
                    window.Echo.private('tenant.{{ auth()->user()->tenant_id }}')
                        .listen('NewApplicationReceived', (e) => {
                            const el = document.getElementById('live-notification');
                            el.textContent = `New application: ${e.candidateName} for ${e.jobTitle}`;
                            el.classList.remove('hidden');
                            setTimeout(() => el.classList.add('hidden'), 5000);
                        })
                        .listen('ScorecardSubmitted', (e) => {
                            const el = document.getElementById('live-notification');
                            el.textContent = `${e.reviewerName} submitted a scorecard for ${e.candidateName}`;
                            el.classList.remove('hidden');
                            setTimeout(() => el.classList.add('hidden'), 5000);
                        });
                }
            });
        </script>
    @endauth

    {{ $slot }}

    @livewireScripts
</body>
</html>
