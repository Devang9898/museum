<?php

// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;
// use Filament\Facades\Filament; // Import facade
// use App\Models\Artwork;

// class TenantPriceChart extends ChartWidget
// {
//     // Heading reflecting the axes
//     protected static ?string $heading = 'Artwork Price by Title';
//     protected static ?int $sort = 2; // Adjust sort order if needed
//     protected static ?string $maxHeight = '300px';

//     /**
//      * Get the type of chart to be rendered.
//      */
//     protected function getType(): string
//     {
//         return 'bar'; // <-- Ensure it's LINE CHART
//     }

//     /**
//      * Get the data for the chart.
//      * Labels = Titles (X-axis categories)
//      * Data = Prices (Y-axis values)
//      */
//     protected function getData(): array
//     {
//         $tenant = Filament::getTenant();
//         if (!$tenant) {
//             return ['datasets' => [], 'labels' => []];
//         }

//         // Fetch artworks with prices for the current tenant
//         // Order by title for a consistent X-axis category order
//         $artworks = Artwork::query()
//             ->where('tenant_id', $tenant->id)
//             ->whereNotNull('price')
//             ->where('price', '>', 0) // Optional: exclude free items?
//             ->orderBy('title', 'asc') // Order by title for X-axis labels
//             ->limit(25) // Limit points for readability on a line chart
//             ->select(['price', 'title'])
//             ->get();

//         // Prepare colors for points/lines
//         $borderColor = '#4BC0C0'; // Example Teal
//         $pointBackgroundColor = '#4BC0C0';

//         return [
//             'datasets' => [
//                 [
//                     'label' => 'Price (€)', // Legend label (adjust currency)
//                     // Prices map to the Y-axis values
//                     'data' => $artworks->pluck('price')->map(fn($price) => (float)$price)->toArray(),
//                     'fill' => false,
//                     'borderColor' => $borderColor,
//                     'pointBackgroundColor' => $pointBackgroundColor,
//                     'tension' => 0.1
//                 ],
//             ],
//             // Titles map to the X-axis category labels
//             'labels' => $artworks->pluck('title')->toArray(),
//         ];
//     }

//     /**
//      * Configure chart options for a line chart with:
//      * X-axis: Categorical (Artwork Titles)
//      * Y-axis: Numerical (Price)
//      */
//     protected function getOptions(): array
//     {
//         return [
//             'scales' => [
//                 'y' => [ // Configure Y axis (Price)
//                     'beginAtZero' => true,
//                     'title' => [
//                         'display' => true,
//                         'text' => 'Price (€)', // Y-axis Title (adjust currency)
//                     ],
//                      'ticks' => [
//                          'callback' => 'js:function(value) {
//                              // Format Y-axis numerical values as currency
//                              return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(value);
//                          }',
//                      ],
//                 ],
//                  'x' => [ // Configure X axis (Artwork Title)
//                      'title' => [
//                          'display' => true,
//                          'text' => 'Artwork Title', // X-axis Title
//                      ],
//                      'ticks' => [
//                          'callback' => 'js:function(value) {
//                              // Get the label (title) for the current index (value)
//                              const label = this.getLabelForValue(value);
//                              // Shorten labels on the axis if they are too long
//                              return label.length > 15 ? label.substring(0, 12) + "..." : label;
//                           }',
//                      ],
//                  ],
//             ],
//             'plugins' => [
//                 'tooltip' => [
//                     'callbacks' => [
//                         // Format tooltip value (label line) as currency
//                         'label' => 'js:(context) => {
//                             let label = context.dataset.label || ""; // e.g., "Price (€)"
//                             if (label) { label += ": "; }
//                             if (context.parsed.y !== null) {
//                                 // Format the Y value (price)
//                                 label += new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(context.parsed.y);
//                             }
//                             return label;
//                         }',
//                         // Title of tooltip is the artwork title (X-axis label)
//                          'title' => 'js:(context) => {
//                              return context[0].label; // Get the full label for the hovered point
//                          }',
//                     ],
//                 ],
//             ],
//             'elements' => [ // Ensure points are visible
//                 'point' => [
//                     'radius' => 3,
//                     'hoverRadius' => 5,
//                 ]
//             ]
//         ];
//     }
// }
// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;
// use Filament\Facades\Filament; // Import facade
// use App\Models\Artwork;

// class TenantPriceChart extends ChartWidget // Keeping class name
// {
//     // Update heading
//     protected static ?string $heading = 'Artwork Price Comparison';
//     protected static ?int $sort = 2; // Adjust sort order if needed
//     protected static ?string $maxHeight = '400px'; // Maybe taller for horizontal bars

//     /**
//      * Get the type of chart to be rendered.
//      */
//     protected function getType(): string
//     {
//         return 'line'; // Use Bar Chart type
//     }

//     /**
//      * Get the data for the chart.
//      */
//     protected function getData(): array
//     {
//         $tenant = Filament::getTenant();
//         if (!$tenant) {
//             return ['datasets' => [], 'labels' => []];
//         }

//         // Fetch artworks with prices for the current tenant
//         // Order by price descending so highest price is at the top (Y-axis)
//         $artworks = Artwork::query()
//             ->where('tenant_id', $tenant->id)
//             ->whereNotNull('price')
//             ->where('price', '>', 0)
//             ->orderBy('price', 'desc') // Order by price
//             ->limit(15) // Limit items for clarity
//             ->select(['price', 'title'])
//             ->get();

//         // Prepare colors
//         $colors = ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

//         return [
//             'datasets' => [
//                 [
//                     'label' => 'Price (€)', // Adjust currency/label
//                     // Map prices to the data array
//                     'data' => $artworks->pluck('price')->map(fn($price) => (float)$price)->toArray(),
//                     'backgroundColor' => $colors,
//                     // No border color needed usually for horizontal bars unless desired
//                 ],
//             ],
//             // Map titles to the labels array for the Y-axis categories
//             'labels' => $artworks->pluck('title')->toArray(),
//         ];
//     }

//     /**
//      * Configure chart options for a HORIZONTAL bar chart.
//      */
//     protected function getOptions(): array
//     {
//         return [
//             // --- MAKE BARS HORIZONTAL ---
//             'indexAxis' => 'y', // <-- Set index axis to Y
//             // --- END HORIZONTAL CONFIG ---

//             'scales' => [
//                 'y' => [ // Configure Y axis (Now Artwork Title)
//                     'beginAtZero' => true, // Still good practice
//                     'title' => [
//                         'display' => true,
//                         'text' => 'Artwork Title',
//                     ],
//                     'ticks' => [ // Shorten labels on Y axis if needed
//                          'callback' => 'js:function(value) {
//                              const label = this.getLabelForValue(value);
//                              return label.length > 25 ? label.substring(0, 22) + "..." : label;
//                           }',
//                      ],
//                 ],
//                  'x' => [ // Configure X axis (Now Price)
//                      'title' => [
//                          'display' => true,
//                          'text' => 'Price (€)', // Adjust currency
//                      ],
//                      'ticks' => [ // Format X-axis ticks as currency
//                          'callback' => 'js:function(value) {
//                              return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(value);
//                          }',
//                      ],
//                  ],
//             ],
//             'plugins' => [
//                 'tooltip' => [
//                     'callbacks' => [
//                         // Format tooltip value as currency
//                         'label' => 'js:(context) => {
//                             let label = context.dataset.label || "";
//                             if (label) { label += ": "; }
//                             // Use context.parsed.x for horizontal bars
//                             if (context.parsed.x !== null) {
//                                 label += new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(context.parsed.x);
//                             }
//                             return label;
//                         }',
//                         // Title of tooltip remains the artwork title (Y-axis label)
//                          'title' => 'js:(context) => {
//                              return context[0].label;
//                          }',
//                     ],
//                 ],
//             ],
//             // Optional: Maintain aspect ratio
//             // 'maintainAspectRatio' => false,
//         ];
//     }
// }
// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;
// use Filament\Facades\Filament; // Import facade
// use App\Models\Artwork;

// class TenantPriceChart extends ChartWidget // Keeping class name
// {
//     // Update heading
//     protected static ?string $heading = 'Artwork Prices';
//     protected static ?int $sort = 2; // Adjust sort order if needed
//     protected static ?string $maxHeight = '300px';

//     /**
//      * Get the type of chart to be rendered.
//      */
//     protected function getType(): string
//     {
//         return 'line'; // <-- CHANGE BACK TO LINE CHART
//     }

//     /**
//      * Get the data for the chart.
//      */
//     protected function getData(): array
//     {
//         $tenant = Filament::getTenant();
//         if (!$tenant) {
//             return ['datasets' => [], 'labels' => []];
//         }

//         // Fetch artworks with prices for the current tenant
//         // Order by title alphabetically for a more sensible X-axis order
//         $artworks = Artwork::query()
//             ->where('tenant_id', $tenant->id)
//             ->whereNotNull('price')
//             ->where('price', '>', 0)
//             ->orderBy('title', 'asc') // Order by title now
//             ->limit(20) // Limit number of points on the line
//             ->select(['price', 'title']) // Select needed fields
//             ->get();

//         // Prepare colors for points/lines
//         $borderColor = '#36A2EB'; // Example blue
//         $pointBackgroundColor = '#36A2EB';

//         return [
//             'datasets' => [
//                 [
//                     'label' => 'Price (€)', // Adjust currency/label
//                     // Map prices to the data array
//                     'data' => $artworks->pluck('price')->map(fn($price) => (float)$price)->toArray(),
//                     'fill' => false, // Don't fill area under line
//                     'borderColor' => $borderColor,
//                     'pointBackgroundColor' => $pointBackgroundColor,
//                     'tension' => 0.1 // Slight curve to the line
//                 ],
//             ],
//             // Map titles to the labels array for the X-axis categories
//             'labels' => $artworks->pluck('title')->toArray(),
//         ];
//     }

//     /**
//      * Configure chart options for a line chart with categorical X-axis.
//      */
//     protected function getOptions(): array
//     {
//         return [
//             'scales' => [
//                 'y' => [ // Configure Y axis (Price)
//                     'beginAtZero' => true,
//                     'title' => [
//                         'display' => true,
//                         'text' => 'Price (€)', // Adjust currency
//                     ],
//                      'ticks' => [
//                          'callback' => 'js:function(value) {
//                              return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(value);
//                          }',
//                      ],
//                 ],
//                  'x' => [ // Configure X axis (Title - treated as category)
//                      'title' => [
//                          'display' => true,
//                          'text' => 'Artwork Title',
//                      ],
//                      // Add callback to shorten long labels if needed
//                      'ticks' => [
//                          'callback' => 'js:function(value) {
//                              const label = this.getLabelForValue(value);
//                              // Shorten labels longer than ~20 chars for axis readability
//                              return label.length > 20 ? label.substring(0, 18) + "..." : label;
//                           }',
//                      ],
//                  ],
//             ],
//             // Tooltip configuration for line chart
//             'plugins' => [
//                 'tooltip' => [
//                     'callbacks' => [
//                         // Format tooltip value as currency
//                         'label' => 'js:(context) => {
//                             let label = context.dataset.label || "";
//                             if (label) { label += ": "; }
//                             if (context.parsed.y !== null) {
//                                 label += new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(context.parsed.y);
//                             }
//                             return label;
//                         }',
//                         // Title of tooltip remains the artwork title (category label)
//                          'title' => 'js:(context) => {
//                              // Access the full label (title) for the tooltip title
//                              return context[0].label;
//                          }',
//                     ],
//                 ],
//             ],
//             // Ensure points are shown
//             'elements' => [
//                 'point' => [
//                     'radius' => 3, // Adjust point size
//                     'hoverRadius' => 5,
//                 ]
//             ]
//         ];
//     }
// }
//without y axis title and without price
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Facades\Filament; // Import facade
use App\Models\Artwork;

class TenantPriceChart extends ChartWidget // Keeping class name for now
{
    // Update heading to reflect new chart type
    protected static ?string $heading = 'Artwork Price Comparison';
    protected static ?int $sort = 2; // Adjust sort order if needed
    protected static ?string $maxHeight = '300px';

    /**
     * Get the type of chart to be rendered.
     */
    protected function getType(): string
    {
        return 'bar'; // <-- CHANGE TO BAR CHART
    }

    /**
     * Get the data for the chart.
     */
    protected function getData(): array
    {
        $tenant = Filament::getTenant();
        if (!$tenant) {
            return ['datasets' => [], 'labels' => []];
        }

        // Fetch artworks with prices for the current tenant
        // Order by price descending maybe? Or title? Limit for clarity.
        $artworks = Artwork::query()
            ->where('tenant_id', $tenant->id)
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->orderBy('price', 'desc') // Show highest price first
            ->limit(15) // Limit the number of artworks shown for readability
            ->select(['price', 'title']) // Select needed fields
            ->get();

        // Prepare colors
        $colors = ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

        return [
            'datasets' => [
                [
                    'label' => 'Price (€)', // Adjust currency/label
                    // Map prices to the data array
                    'data' => $artworks->pluck('price')->map(fn($price) => (float)$price)->toArray(),
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                ],
            ],
            // Map titles to the labels array for the X-axis
            'labels' => $artworks->pluck('title')->toArray(),
        ];
    }

    /**
     * Configure chart options for a bar chart.
     */
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [ // Configure Y axis (Price)
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Price (€)', // Adjust currency
                    ],
                     // Optional: Format Y-axis ticks as currency
                     'ticks' => [
                         'callback' => 'js:function(value) {
                             return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(value);
                         }',
                     ],
                ],
                 'x' => [ // Configure X axis (Title)
                     'title' => [
                         'display' => true,
                         'text' => 'Artwork Title',
                     ],
                     // Prevent labels from getting too long/overlapping if needed
                     // 'ticks' => [
                     //     'callback' => 'js:function(value) {
                     //         const label = this.getLabelForValue(value);
                     //         return label.length > 15 ? label.substring(0, 12) + "..." : label;
                     //      }',
                     // ],
                 ],
            ],
            // Tooltip configuration for bar chart
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        // Format tooltip value as currency
                        'label' => 'js:(context) => {
                            let label = context.dataset.label || "";
                            if (label) { label += ": "; }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(context.parsed.y);
                            }
                            return label;
                        }',
                        // Title of tooltip remains the artwork title (category label)
                         'title' => 'js:(context) => {
                             return context[0].label;
                         }',
                    ],
                ],
            ],
        ];
    }
}
////without x axis title
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