<?php

namespace App\Filament\Superadmin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Tenant;
use App\Models\Artwork;
use Illuminate\Support\Facades\DB; // Import DB Facade for raw query/aggregation

class TotalValuePerTenantChart extends ChartWidget
{
    protected static ?string $heading = 'Total Artwork Value per Organization';
    protected static ?int $sort = 2; // Adjust sort order as needed

    // Remove this line if you add getType()
    // protected string $type = 'bar';

    protected static ?string $maxHeight = '300px'; // Optional: Control height

    /**
     * Get the type of chart to be rendered.
     * REQUIRED implementation for ChartWidget.
     */
    protected function getType(): string // <-- IMPLEMENT THIS METHOD
    {
        // Choose 'bar' or 'line' based on your preference
        return 'bar';
        // return 'line';
    }

    /**
     * Get the data for the chart.
     */
    protected function getData(): array
    {
        // --- IMPORTANT ASSUMPTION ---
        // Assumes 'artworks' table has a numeric 'price' column.
        // Adjust 'SUM(artworks.price)' if needed.

        $data = Artwork::query()
            ->join('tenants', 'artworks.tenant_id', '=', 'tenants.id')
            ->select(
                'tenants.name as tenant_name',
                DB::raw('SUM(artworks.price) as total_value')
            )
            ->groupBy('tenants.id', 'tenants.name')
            ->orderBy('tenants.name')
            ->get();

        // Prepare colors - add more if needed
        $colors = ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

        return [
            'datasets' => [
                [
                    'label' => 'Total Artwork Value (€)', // Adjust currency/label
                    'data' => $data->pluck('total_value')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $data->count()), // Use colors
                    'borderColor' => array_slice($colors, 0, $data->count()), // Use colors
                ],
            ],
            'labels' => $data->pluck('tenant_name')->toArray(),
        ];
    }
}