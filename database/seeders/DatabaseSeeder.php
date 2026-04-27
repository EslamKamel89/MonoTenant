<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public $companies = [
        'NexaSoft Solutions',
        'BluePeak Technologies',
        'Orion Digital Labs',
        'Vertex Dynamics',
        'CloudNova Systems',
    ];
    public function run(): void {
        foreach ($this->companies as $company) {
            $masterTenant = Tenant::create([
                'name' => $company
            ]);
        }
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'tenant_id' => Tenant::first()->id,
        ]);
    }
}
