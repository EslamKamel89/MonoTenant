<?php

namespace App\Http\Middleware;

use App\Context\TenantContext;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureValidTenantAccess {


    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $slug = request()->route('tenant');
        $pathTenant = Tenant::where('slug', $slug)->first();
        $currentUserTenant =  auth()->user()->tenant;
        if (!$pathTenant || $pathTenant->id !== $currentUserTenant->id) {
            throw new AccessDeniedHttpException();
        }
        return $next($request);
    }
}
