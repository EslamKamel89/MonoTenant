<?php

namespace App\Http\Middleware;

use App\Context\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (auth()->check()) {
            TenantContext::setTenantId(auth()->user()->tenant_id);
        }
        return $next($request);
    }
}
