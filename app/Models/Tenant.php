<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // For UUID primary keys
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // For one-to-many relationships

class Tenant extends Model
{
    // Use standard Laravel traits and enable UUIDs
    use HasFactory, HasUuids;

    // Configuration for UUID primary key
    public $incrementing = false; // Primary key is not auto-incrementing
    protected $keyType = 'string'; // Primary key type is string (for UUID)

    /**
     * The attributes that are mass assignable.
     * These fields can be set using Tenant::create([...]) or Tenant::update([...])
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
    ];

    /**
     * The attributes that should be cast to native types.
     * Ensures the 'id' is treated as a string.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

    /**
     * Get the TenantAdmin users associated with this Tenant.
     * Defines a one-to-many relationship: One Tenant has many TenantAdmins.
     */
    public function tenantAdmins(): HasMany
    {
        return $this->hasMany(TenantAdmin::class);
    }

    /**
     * Get the Artworks associated with this Tenant.
     * Defines a one-to-many relationship: One Tenant has many Artworks.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }

    /**
     * Get the Categories associated with this Tenant.
     * Defines a one-to-many relationship: One Tenant has many Categories.
     * This is essential for tenant-specific categories.
     */
    public function categories(): HasMany // This is the added relationship
    {
        return $this->hasMany(Category::class);
    }
}