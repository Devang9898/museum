<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Artwork extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'category_id',
        'title',
        'image',
        'length',
        'breadth',
        'price',
    ];

    protected $casts = [
        'id' => 'string',
        'tenant_id' => 'string',
        'length' => 'decimal:2',
        'breadth' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the artwork.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the category that the artwork belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
