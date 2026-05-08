<?php

namespace App\Http\Middleware;

use App\Context\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantContext {


    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (auth()->check()) {
            TenantContext::set(auth()->user()->tenant);
        }
        return $next($request);
    }
}
