<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    // Timestamps are still false based on the original schema request
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * Includes tenant_id now.
     */
    protected $fillable = [
        'tenant_id', // Added
        'name',
    ];

    /**
     * The attributes that should be cast.
     * Casts tenant_id (UUID) to string.
     *
     * @var array
     */
    protected $casts = [
        'tenant_id' => 'string', // Added
    ];


    /**
     * Get the tenant that owns the category.
     */
    public function tenant(): BelongsTo // Defines the relationship to Tenant
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the artworks within this category.
     * This relationship remains unchanged structurally.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }
}