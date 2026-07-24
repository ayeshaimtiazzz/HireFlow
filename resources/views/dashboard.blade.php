<x-layouts.app title="Dashboard">
<div class="max-w-4xl mx-auto mt-10 px-4">
    <h1 class="text-2xl font-bold mb-2">Welcome, {{ auth()->user()->name }}</h1>
    <p class="text-gray-500 mb-6">
        Role: {{ auth()->user()->getRoleNames()->first() ?? 'No role assigned' }}
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @can('create-job')
            <a href="/jobs" class="bg-white p-6 rounded shadow block hover:shadow-md">
                <h2 class="font-semibold mb-2">Job Postings</h2>
                <p class="text-sm text-gray-500">Manage your open roles.</p>
            </a>
        @endcan

        @if (auth()->user()->hasRole('company_admin'))
            <a href="/team" class="bg-white p-6 rounded shadow block hover:shadow-md">
                <h2 class="font-semibold mb-2">Team Management</h2>
                <p class="text-sm text-gray-500">Invite teammates, manage roles.</p>
            </a>
        @endif
    </div>
</div>
</x-layouts.app>
