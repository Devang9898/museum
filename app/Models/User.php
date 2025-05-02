<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser; // Import
use Filament\Panel; // Import

class User extends Authenticatable implements FilamentUser // Implement FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin', // Add
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array // Use method for casts in newer Laravel
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean', // Add cast
        ];
    }

    // Implement canAccessPanel for Filament
    public function canAccessPanel(Panel $panel): bool
    {
        // Only allow access to the 'superadmin' panel if is_super_admin is true
        if ($panel->getId() === 'superadmin') {
            return $this->is_super_admin;
        }

        // Deny access to other panels (like 'admin') by default for this User model
        // Or add logic for other panels if needed
        return false;
    }
}