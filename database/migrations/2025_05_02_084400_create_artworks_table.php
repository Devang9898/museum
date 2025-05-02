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

            // Foreign key for tenant
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();

            // Foreign key for category
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            $table->string('title');
            $table->string('image')->nullable(); // Path or URL to the image

            // New fields for dimensions and price
            $table->decimal('length', 8, 2)->nullable();   // Length in cm/inches
            $table->decimal('breadth', 8, 2)->nullable();  // Breadth in cm/inches
            $table->decimal('price', 12, 2)->nullable();   // Price of the artwork

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

