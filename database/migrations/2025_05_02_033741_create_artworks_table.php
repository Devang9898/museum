<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID Primary Key
            // Ensure tenant_id is UUID type and references the tenants table's id
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            // Foreign key for category (assuming INT id for categories)
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete(); // Allow null, set null if category deleted
            $table->string('title');
            $table->string('image')->nullable(); // Path or URL to the image
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};