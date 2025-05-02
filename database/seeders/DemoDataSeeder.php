<?php

namespace Database\Seeders;

// Import necessary classes
// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Often not needed unless excluding specific listeners
use Illuminate\Database\Seeder; // Base Seeder class
use App\Models\Tenant;
use App\Models\TenantAdmin;
use App\Models\Category;
use Illuminate\Support\Str; // For UUID generation
use Illuminate\Support\Facades\Hash; // For password hashing
// use Illuminate\Support\Facades\DB; // Not strictly needed for this seeder, but can be useful

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates demo tenants, tenant admins, and tenant-specific categories.
     */
    public function run(): void
    {
        // Using $this->command->info() provides output when running db:seed
        $this->command->info('Seeding initial Tenants, Admins, and Categories...');

        // --- Create Tenants ---
        // Use firstOrCreate: find by 'slug' or create if it doesn't exist
        $tenant1 = Tenant::firstOrCreate(
            ['slug' => 'moma'], // Attributes to find by
            [                  // Attributes to use if creating new
                'id' => Str::uuid()->toString(),
                'name' => 'Museum of Modern Art',
                'email' => 'contact@moma.example.com'
            ]
        );
        $this->command->info("Tenant '{$tenant1->name}' [{$tenant1->slug}] created or found.");

        $tenant2 = Tenant::firstOrCreate(
            ['slug' => 'national-gallery'], // Attributes to find by
            [                               // Attributes to use if creating new
                'id' => Str::uuid()->toString(),
                'name' => 'National Gallery',
                'email' => 'info@nationalgallery.example.com'
            ]
        );
        $this->command->info("Tenant '{$tenant2->name}' [{$tenant2->slug}] created or found.");


        // --- Create Tenant Admins ---
        // Use firstOrCreate: find by 'email' or create if it doesn't exist
        TenantAdmin::firstOrCreate(
            ['email' => 'alice@moma.example.com'], // Attributes to find by
            [                                     // Attributes to use if creating new
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant1->id, // Link to the first tenant
                'name' => 'Alice Admin',
                'password' => Hash::make('password'), // Hash the password
                'email_verified_at' => now()       // Mark email as verified
            ]
        );
        $this->command->info("Admin 'Alice Admin' for '{$tenant1->name}' created or found.");

        TenantAdmin::firstOrCreate(
            ['email' => 'bob@nationalgallery.example.com'], // Attributes to find by
            [                                              // Attributes to use if creating new
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant2->id, // Link to the second tenant
                'name' => 'Bob Curator',
                'password' => Hash::make('password'), // Hash the password
                'email_verified_at' => now()       // Mark email as verified
            ]
        );
        $this->command->info("Admin 'Bob Curator' for '{$tenant2->name}' created or found.");


        // --- Create Categories (Tenant-Specific) ---
        // Use firstOrCreate including tenant_id for uniqueness *within* each tenant
        Category::firstOrCreate(
            // Attributes to find by (unique combination within the tenant)
            ['name' => 'Painting', 'tenant_id' => $tenant1->id],
            // Attributes to use if creating new (repeating is okay for firstOrCreate)
            ['name' => 'Painting', 'tenant_id' => $tenant1->id]
        );
        Category::firstOrCreate(
            ['name' => 'Sculpture', 'tenant_id' => $tenant1->id],
            ['name' => 'Sculpture', 'tenant_id' => $tenant1->id]
        );
        $this->command->info("Categories for '{$tenant1->name}' created or found.");

        Category::firstOrCreate(
            ['name' => 'Photography', 'tenant_id' => $tenant2->id],
            ['name' => 'Photography', 'tenant_id' => $tenant2->id]
        );
         Category::firstOrCreate(
            ['name' => 'Digital Art', 'tenant_id' => $tenant2->id],
            ['name' => 'Digital Art', 'tenant_id' => $tenant2->id]
        );
        $this->command->info("Categories for '{$tenant2->name}' created or found.");

        $this->command->info('DemoDataSeeder completed successfully.');
    }
}