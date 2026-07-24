<!DOCTYPE html>
<html>
<head>
    <title>Interview confirmed</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen p-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow text-center">
        <h1 class="text-xl font-bold text-teal-700 mb-2">You're booked!</h1>
        <p class="text-gray-600">{{ $slot->starts_at->format('l, M j \a\t g:i A') }}</p>
        @if ($slot->location)
            <p class="text-gray-500 text-sm mt-2">{{ $slot->location }}</p>
        @endif
        <p class="text-gray-400 text-sm mt-4">A confirmation has been noted. See you then!</p>
    </div>
</body>
</html>
