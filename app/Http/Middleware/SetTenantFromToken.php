<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantFromToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->tenant_id) {
            abort(401, 'No valid tenant associated with this token.');
        }

        $tenant = Tenant::find($user->tenant_id);
        $tenant->makeCurrent();

        return $next($request);
    }
}
