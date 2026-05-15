<?php


namespace App\Services;

use Illuminate\Support\Facades\DB;

class TenantService {

    static public function createDb(string $tenantDbName) {
        DB::statement('CREATE DATABASE ' . $tenantDbName . ' ;');
    }
}
