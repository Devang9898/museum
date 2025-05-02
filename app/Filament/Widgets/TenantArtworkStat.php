<?php

// namespace App\Filament\Widgets;

// use Filament\Widgets\StatsOverviewWidget as BaseWidget;
// use Filament\Widgets\StatsOverviewWidget\Stat;
// use Filament\Facades\Filament; // Import facade
// use App\Models\Artwork;      // Import model

// class TenantArtworkStat extends BaseWidget
// {
//     protected static ?int $sort = -2; // Place it high

//     protected function getStats(): array
//     {
//         $tenant = Filament::getTenant(); // Get current tenant context

//         if (!$tenant) {
//             return [Stat::make('Error', 'Tenant context not found')->color('danger')];
//         }

//         $count = Artwork::where('tenant_id', $tenant->id)->count();

//         return [
//             Stat::make('Total Artworks', $count)
//                 ->description('Your organization\'s collection')
//                 ->descriptionIcon('heroicon-m-photo')
//                 ->chart($this->generatePreviousMonthsCounts($tenant->id)) // Optional: Add a simple chart
//                 ->color('primary'),
//         ];
//     }

//     // Optional helper to generate simple chart data for the Stat
//     protected function generatePreviousMonthsCounts(string $tenantId): array
//     {
//          // Get counts for the last 6 months for the specific tenant
//          $counts = Artwork::query()
//              ->where('tenant_id', $tenantId)
//              ->selectRaw("COUNT(*) as count, DATE_FORMAT(created_at, '%Y-%m') as month")
//              ->where('created_at', '>=', now()->subMonths(5)->startOfMonth()) // Last 6 months including current
//              ->groupBy('month')
//              ->orderBy('month', 'asc')
//              ->pluck('count', 'month')
//              ->toArray();

//          // Prepare chart data for the last 6 months
//          $chartData = [];
//          for ($i = 5; $i >= 0; $i--) {
//              $month = now()->subMonths($i)->format('Y-m');
//              $chartData[] = $counts[$month] ?? 0; // Use count or 0 if no data for that month
//          }
//          return $chartData;
//     }
// }