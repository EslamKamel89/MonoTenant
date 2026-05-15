<?php

namespace App\Context;

use App\Models\Tenant;

class TenantContext {
    private static ?Tenant $tenant = null;
    public static function set(Tenant $tenant) {
        static::$tenant = $tenant;
    }
    public static function get() {
        return static::$tenant;
    }
}
