<?php

// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;
// use Filament\Facades\Filament; // Import facade
// use App\Models\Artwork;
// use App\Models\Tenant;      // Import Tenant (though not strictly needed if Filament::getTenant() works)
// use App\Models\Category;   // Import Category
// use Illuminate\Support\Facades\DB; // Import DB Facade

// class TenantCategoryValueChart extends ChartWidget
// {
//     protected static ?string $heading = 'Total Artwork Value by Category';
//     protected static ?int $sort = 4; // Adjust sort order as needed (e.g., after Price Scatter)
//     protected static ?string $maxHeight = '300px';

//     // Implement the required getType method
//     protected function getType(): string
//     {
//         return 'pie';
//     }

//         // Inside TenantCategoryValueChart::getData() method

//         protected function getData(): array
//         {
//             $tenant = Filament::getTenant();
//             if (!$tenant) {
//                 return ['datasets' => [], 'labels' => []];
//             }
    
//             $tenantId = $tenant->id; // Store ID for easier use
    
//             $data = Artwork::query()
//                 // Explicitly use artworks.tenant_id in the initial where clause
//                 ->where('artworks.tenant_id', $tenantId)
//                 ->whereNotNull('artworks.price')
//                 ->where('artworks.price', '>', 0)
//                 ->whereNotNull('artworks.category_id')
//                 // Join categories, also ensuring the joined category belongs to the tenant
//                 ->join('categories', function ($join) use ($tenantId) {
//                     $join->on('artworks.category_id', '=', 'categories.id')
//                          ->where('categories.tenant_id', '=', $tenantId); // Qualify tenant_id here too
//                 })
//                 ->select(
//                     'categories.name as category_name',
//                     DB::raw('SUM(CAST(artworks.price AS DECIMAL(10,2))) as total_value')
//                 )
//                 // Group by category columns (already qualified)
//                 ->groupBy('categories.id', 'categories.name')
//                 ->orderByDesc('total_value')
//                 ->limit(8)
//                 ->get();
    
//             // Prepare colors
//             $colors = [ '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56', '#9966FF', '#FF9F40', '#8D6E63', '#EC407A'];
    
//             return [
//                 'datasets' => [ /* ... as before ... */ ],
//                 'labels' => $data->pluck('category_name')->toArray(),
//             ];
//         }

//     /**
//      * Optional: Configure chart options for better tooltips (e.g., currency formatting)
//      */
//     protected function getOptions(): array
//     {
//         return [
//             'plugins' => [
//                 'tooltip' => [
//                     'callbacks' => [
//                         // Format tooltip value as currency (adjust locale/currency as needed)
//                         'label' => 'js:(context) => {
//                             let label = context.label || "";
//                             if (label) { label += ": "; }
//                             if (context.parsed !== null) {
//                                 label += new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(context.parsed);
//                             }
//                             return label;
//                         }',
//                     ],
//                 ],
//                 // Example: Add ChartJS Datalabels plugin (if installed separately)
//                 // 'datalabels' => [
//                 //     'display' => true,
//                 //     'formatter' => '(value, ctx) => {
//                 //         let sum = 0;
//                 //         let dataArr = ctx.chart.data.datasets[0].data;
//                 //         dataArr.map(data => { sum += data; });
//                 //         let percentage = (value*100 / sum).toFixed(1)+"%";
//                 //         return percentage;
//                 //     }',
//                 //     'color' => '#fff',
//                 // ]
//             ],
//         ];
//     }
// }