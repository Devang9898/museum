<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the categories table with tenant relationship included.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Auto-incrementing INT primary key for Category itself

            // Foreign key linking to the tenants table (using UUID)
            $table->foreignUuid('tenant_id')
                  ->constrained('tenants') // Links to the 'id' (UUID) on the 'tenants' table
                  ->cascadeOnDelete();     // If a tenant is deleted, delete their categories too

            $table->string('name');

            // Add unique constraint: A tenant cannot have two categories with the same name
            $table->unique(['tenant_id', 'name']);

            // Timestamps are generally recommended, but sticking to original schema request
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};