<?php

namespace App\Http\Middleware;

use App\Context\TenantContext;
use App\Models\Tenant;
use App\Services\TenantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TenantMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $tenantSlug = $request->route('tenant');
        if (!$tenantSlug) {
            throw new AccessDeniedHttpException();
        }
        $tenant = Tenant::where('slug', $tenantSlug)->first();
        if (!$tenant) {
            throw new AccessDeniedHttpException();
        }
        TenantContext::set($tenant);
        TenantService::connectToDb($tenant);
        TenantService::migrateDb();
        return $next($request);
    }
}
