<?php


namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantService {
    public static function createDb(string $tenantDbName) {
        DB::statement('CREATE DATABASE ' . $tenantDbName . ' ;');
    }
    public static function connectToDb(Tenant $tenant) {
        if (Config::get('database.connections.tenant.database') != $tenant->database || Config::get('database.default') != 'tenant') {
            Config::set('database.connections.tenant.database', $tenant->database);
            Config::set('database.default', 'tenant');
            DB::purge('tenant');
            DB::reconnect('tenant');
        }
        dump(DB::connection()->getDatabaseName());
    }
    public static function migrateDb() {
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenants'
        ]);
    }
}
