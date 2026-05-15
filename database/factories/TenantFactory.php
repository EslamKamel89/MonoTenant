<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $name  = $this->faker->company();
        $slug = str($name)->slug();
        return [
            'created_by' => User::factory(),
            'owner_id' => User::factory(),
            'name' => $name,
            'slug' => $slug,
            'subdomain' => $slug,
            'database' => 'tenant_' . Str::random(10)
        ];
    }
}
