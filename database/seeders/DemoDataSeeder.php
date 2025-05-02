<?php

namespace Database\Seeders;

// Base Seeder class
use Illuminate\Database\Seeder;

// Models
use App\Models\Tenant;
use App\Models\TenantAdmin;
use App\Models\Category;
use App\Models\User; // <-- Import User model

// Helpers
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates demo tenants, tenant admins, tenant-specific categories, and a Super Admin user.
     */
    public function run(): void
    {
        $this->command->info('Seeding initial Tenants, Admins, and Categories...');

        // --- Create Tenants ---
        $tenant1 = Tenant::firstOrCreate(
            ['slug' => 'moma'],
            ['id' => Str::uuid()->toString(), 'name' => 'Museum of Modern Art', 'email' => 'contact@moma.example.com']
        );
        $this->command->info("Tenant '{$tenant1->name}' [{$tenant1->slug}] created or found.");

        $tenant2 = Tenant::firstOrCreate(
            ['slug' => 'national-gallery'],
            ['id' => Str::uuid()->toString(), 'name' => 'National Gallery', 'email' => 'info@nationalgallery.example.com']
        );
        $this->command->info("Tenant '{$tenant2->name}' [{$tenant2->slug}] created or found.");


        // --- Create Tenant Admins ---
        TenantAdmin::firstOrCreate(
            ['email' => 'alice@moma.example.com'],
            ['id' => Str::uuid()->toString(), 'tenant_id' => $tenant1->id, 'name' => 'Alice Admin', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
        $this->command->info("Admin 'Alice Admin' for '{$tenant1->name}' created or found.");

        TenantAdmin::firstOrCreate(
            ['email' => 'bob@nationalgallery.example.com'],
            ['id' => Str::uuid()->toString(), 'tenant_id' => $tenant2->id, 'name' => 'Bob Curator', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
        $this->command->info("Admin 'Bob Curator' for '{$tenant2->name}' created or found.");


        // --- Create Categories (Tenant-Specific) ---
        Category::firstOrCreate(
            ['name' => 'Painting', 'tenant_id' => $tenant1->id],
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


        // --- ADD SUPER ADMIN USER ---
        $this->command->info('Creating/finding Super Admin user...');
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'], // Find by unique email
            [                                      // Data if creating new
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Use a strong password in reality
                'email_verified_at' => now(),
                'is_super_admin' => true           // Set the flag
            ]
        );
        $this->command->info('Super Admin user created or found.');
        // --- END SUPER ADMIN USER ---


        $this->command->info('DemoDataSeeder completed successfully.');
    }
}