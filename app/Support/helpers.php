<?php

use App\Context\TenantContext;

if (!function_exists('tenant_route')) {
    function tenant_route(
        string $name,
        array $parameters = [],
        bool $absolute = true
    ): string {
        return route(
            $name,
            [...TenantContext::pathParam(), ...$parameters],
            $absolute,
        );
    }
}
