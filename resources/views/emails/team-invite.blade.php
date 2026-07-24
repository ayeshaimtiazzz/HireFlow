<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; padding: 24px;">
    <h2>You've been invited to join {{ $tenant->name }}</h2>
    <p>You've been invited as a <strong>{{ str_replace('_', ' ', $role) }}</strong>.</p>
    <p>This link expires in 72 hours:</p>
    <a href="{{ $signedUrl }}">{{ $signedUrl }}</a>
</body>
</html>
