<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users', 'id')->restrictOnDelete();
            $table->foreignId('owner_id')->constrained('users', 'id')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subdomain')->unique();
            $table->string('database')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tenants');
    }
};
