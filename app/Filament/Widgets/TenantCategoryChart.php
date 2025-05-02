<?php

// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;
// use Filament\Facades\Filament; // Import facade
// use App\Models\Artwork;
// use Illuminate\Support\Facades\DB;

// class TenantCategoryChart extends ChartWidget
// {
//     protected static ?string $heading = 'Artworks by Category';
//     protected static ?int $sort = 1; // Place after stats
//     protected static ?string $maxHeight = '300px';

//     // Implement the required getType method
//     protected function getType(): string
//     {
//         return 'pie';
//     }

//         // Inside TenantCategoryChart::getData() method

//             // Inside TenantCategoryChart class

//     /**
//      * Get the data for the chart.
//      */
//     protected function getData(): array
//     {
//         $tenant = Filament::getTenant();
//         if (!$tenant) {
//             return ['datasets' => [], 'labels' => []];
//         }

//         $tenantId = $tenant->id; // Store ID for clarity

//         $data = Artwork::query()
//             // --- THIS IS THE CRITICAL LINE ---
//             // Ensure the first 'where' clause uses 'artworks.tenant_id'
//             ->where('artworks.tenant_id', $tenantId)
//             // --- END CRITICAL LINE ---
//             ->whereNotNull('artworks.category_id') // Good practice
//             // Join categories, ensuring the category also belongs to the tenant
//             ->join('categories', function ($join) use ($tenantId) {
//                 $join->on('artworks.category_id', '=', 'categories.id')
//                      ->where('categories.tenant_id', '=', $tenantId); // Also qualify here
//             })
//             ->select(
//                 'categories.name as category_name',
//                 DB::raw('COUNT(artworks.id) as artwork_count') // Correct aggregation (COUNT)
//             )
//             ->groupBy('categories.id', 'categories.name')
//             ->orderByDesc('artwork_count')
//             ->limit(8)
//             ->get();

//         // Prepare colors
//         $colors = [
//             '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
//             '#9966FF', '#FF9F40', '#8D6E63', '#EC407A',
//         ];

//         return [
//             'datasets' => [
//                 [
//                     'label' => 'Artworks',
//                     'data' => $data->pluck('artwork_count')->toArray(),
//                     'backgroundColor' => array_slice($colors, 0, $data->count()),
//                 ],
//             ],
//             'labels' => $data->pluck('category_name')->toArray(),
//         ];
//     }
// }