<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens; // <-- Remove or comment out Passport
use Laravel\Sanctum\HasApiTokens; // <--- USE SANCTUM'S TRAIT INSTEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Filament\Models\Contracts\HasTenants;


class TenantAdmin extends Authenticatable implements FilamentUser, HasTenants
{
    // Use Sanctum's HasApiTokens trait now
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    // ... (rest of your model code remains the same) ...
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
         'email_verified_at', // <-- ADD THIS if you set it in create()
    ];

     protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'string',
        'tenant_id' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ... methods (tenant, canAccessPanel, getTenants, etc.) ...
     public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return !is_null($this->tenant_id) && $this->hasVerifiedEmail();
    }

    public function getTenants(Panel $panel): Collection
    {
        return collect([$this->tenant]);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenant_id === $tenant->getKey();
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->tenant;
    }
}

//php artisan optimize:clear