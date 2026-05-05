<?php

namespace   App\Context;

class TenantContext {
    protected static ?int $tenantId = null;
    public static function setTenantId(int $tenantId): void {
        static::$tenantId = $tenantId;
    }
    public static function getTenantId(): ?int {
        return static::$tenantId;
    }
}
