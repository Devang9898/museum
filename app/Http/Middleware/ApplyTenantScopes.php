<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Artwork; // Import models that need scoping

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current tenant from Filament
        $tenant = Filament::getTenant();

        if ($tenant) {
            // Apply global scope to Artworks
            Artwork::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            });

            // Add scopes for other tenant-specific models here if needed
            // E.g., if TenantAdmins could manage other TenantAdmins within their tenant:
            // TenantAdmin::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
            //    $query->where('tenant_id', $tenant->id);
            // });

        }

        return $next($request);
    }
}