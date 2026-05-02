<?php

namespace Database\Seeders;

use App\Models\Article;
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
            $masterTenant = Tenant::factory()->create([
                'name' => $company
            ])->each(function ($tenant) {
                $users = User::factory()
                    ->count(10)
                    ->create([
                        'tenant_id' => $tenant->id
                    ]);

                foreach ($users as $user) {
                    Article::factory()
                        ->count(5)
                        ->create([
                            'tenant_id' => $tenant->id,
                            'user_id' => $user->id,
                        ]);
                }
            });
        }
        //     Tenant::factory()
        // ->count(5)
        // ->create()

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'tenant_id' => Tenant::first()->id,
        ]);
    }
}
