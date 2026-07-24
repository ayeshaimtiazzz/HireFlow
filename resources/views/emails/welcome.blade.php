<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; padding: 24px;">
    <h2>Welcome to HireFlow, {{ $user->name }}!</h2>
    <p>Your workspace for <strong>{{ $tenant->name }}</strong> is ready.</p>
    <p>You can access your dashboard at:
        <a href="http://{{ $tenant->domain }}/dashboard">{{ $tenant->domain }}/dashboard</a>
    </p>
</body>
</html>
