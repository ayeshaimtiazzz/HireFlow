<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Tenant::checkCurrent()) {
            abort(404, 'This company workspace does not exist.');
        }

        return $next($request);
    }
}
