<?php

namespace   App\Context;

use App\Models\Tenant;

class TenantContext {
    protected static ?Tenant $tenant = null;
    public static function set(Tenant $tenant): void {
        static::$tenant = $tenant;
    }
    public static function tenant(): ?Tenant {
        return static::$tenant;
    }
    public static function id(): ?int {
        return static::$tenant?->id;
    }
    public static function pathParam(): array {
        return ['tenant' => static::$tenant?->slug];
    }
}
