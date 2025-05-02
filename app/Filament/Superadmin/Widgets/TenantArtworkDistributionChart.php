<?php

namespace App\Filament\Superadmin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Tenant;
use App\Models\Artwork;
use Illuminate\Support\Facades\DB;

class TenantArtworkDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Artwork Distribution by Organization';
    protected static ?int $sort = 3; // Adjust sort order if needed

    // Remove or comment out this line, as getType() defines it now
    // protected string $type = 'pie';

    protected static ?string $maxHeight = '300px';

    /**
     * Get the type of chart to be rendered.
     * REQUIRED implementation for ChartWidget.
     */
    protected function getType(): string // <-- THIS METHOD WAS MISSING
    {
        return 'pie'; // Specify the chart type here
    }

    /**
     * Get the data for the chart.
     */
    protected function getData(): array
    {
        $data = Artwork::query()
            ->join('tenants', 'artworks.tenant_id', '=', 'tenants.id')
            ->select(
                'tenants.name as tenant_name',
                DB::raw('COUNT(artworks.id) as artwork_count')
            )
            ->groupBy('tenants.id', 'tenants.name')
            ->orderBy('tenants.name')
            ->get();

        // Prepare colors - add more if needed
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#8D6E63', '#EC407A',
            '#D4E157', '#FF7043', '#78909C', '#26A69A',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Artworks',
                    'data' => $data->pluck('artwork_count')->toArray(),
                    // Ensure enough colors for the data points
                    'backgroundColor' => array_slice($colors, 0, $data->count()),
                ],
            ],
            'labels' => $data->pluck('tenant_name')->toArray(),
        ];
    }
}