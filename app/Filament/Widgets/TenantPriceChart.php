<?php

// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;
// use Filament\Facades\Filament; // Import facade
// use App\Models\Artwork;

// class TenantPriceChart extends ChartWidget
// {
//     protected static ?string $heading = 'Artwork Price Distribution Over Time';
//     protected static ?int $sort = 2; // Place after pie chart
//     protected static ?string $maxHeight = '300px';

//     // Implement required getType method
//     protected function getType(): string
//     {
//         return 'scatter';
//     }

//     protected function getData(): array
//     {
//         $tenant = Filament::getTenant();
//         if (!$tenant) {
//             return ['datasets' => []]; // No labels needed for scatter typically
//         }

//         // Fetch artworks with prices for the current tenant, ordered by creation date
//         $artworks = Artwork::query()
//             ->where('tenant_id', $tenant->id)
//             ->whereNotNull('price') // Only include artworks with a price
//             ->where('price', '>', 0) // Optional: exclude free items?
//             ->orderBy('created_at', 'asc')
//             ->select(['created_at', 'price', 'title']) // Select title for tooltip
//             ->get();

//         // Format data for Chart.js scatter plot: [{x: timestamp, y: price}, ...]
//         $scatterData = $artworks->map(function ($artwork) {
//             return [
//                 'x' => $artwork->created_at->timestamp * 1000, // Use millisecond timestamp for time axis
//                 'y' => (float) $artwork->price, // Ensure price is a float
//                 'title' => $artwork->title // Add title for potential tooltips
//             ];
//         })->toArray();

//         return [
//             'datasets' => [
//                 [
//                     'label' => 'Artwork Price (€)', // Adjust currency/label
//                     'data' => $scatterData,
//                     'backgroundColor' => 'rgba(255, 99, 132, 0.5)', // Example color
//                     'borderColor'     => 'rgb(255, 99, 132)',
//                 ],
//             ],
//             // No 'labels' needed for scatter X-axis if using time/numeric type
//         ];
//     }

//     /**
//      * Configure chart options (especially for time axis)
//      */
//     protected function getOptions(): array
//     {
//         return [
//             'scales' => [
//                 'x' => [
//                     'type' => 'time', // Tell Chart.js the X-axis is time
//                     'time' => [
//                         'unit' => 'day', // Display units as days, adjust as needed ('month', 'year')
//                          'tooltipFormat' => 'MMM dd, yyyy HH:mm', // Format for tooltips
//                     ],
//                     'title' => [
//                         'display' => true,
//                         'text' => 'Date Added',
//                     ],
//                 ],
//                 'y' => [
//                     'beginAtZero' => true,
//                     'title' => [
//                         'display' => true,
//                         'text' => 'Price (€)', // Adjust currency
//                     ],
//                     // Optional: Format ticks as currency
//                     // 'ticks' => [
//                     //    'callback' => fn ($value) => '€' . number_format($value, 2),
//                     // ],
//                 ],
//             ],
//             // Configure tooltips to show artwork title (requires custom callback)
//             'plugins' => [
//                 'tooltip' => [
//                     'callbacks' => [
//                         'label' => 'js:(context) => {
//                             const dataPoint = context.dataset.data[context.dataIndex];
//                             const title = dataPoint.title || ""; // Get the title we added
//                             let label = context.dataset.label || "";
//                             if (label) { label += ": "; }
//                             label += "€" + context.parsed.y.toFixed(2); // Format price
//                             if(title) { label += " (" + title + ")" } // Add title
//                             return label;
//                         }',
//                          'title' => 'js:(context) => {
//                              // Format date from timestamp for tooltip title
//                              const date = new Date(context[0].parsed.x);
//                              return date.toLocaleDateString(undefined, { dateStyle: "medium", timeStyle: "short"});
//                          }',
//                     ],
//                 ],
//             ],
//         ];
//     }
// }