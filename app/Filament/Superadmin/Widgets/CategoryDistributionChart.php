<?php

// namespace App\Filament\Superadmin\Widgets; // Correct Namespace

// use Filament\Widgets\ChartWidget;
// use App\Models\Artwork;
// use App\Models\Tenant;
// use App\Models\Category; // Correct Model
// use Illuminate\Support\Facades\DB;
// use Livewire\Attributes\On;

// // Correct Class Name
// class CategoryDistributionChart extends ChartWidget
// {
//     protected static ?int $sort = 4;
//     protected static ?string $maxHeight = '300px';

//     public ?string $selectedTenantId = null;

//     #[On('tenantSelected')]
//     public function updateTenantContext($tenantId): void
//     {
//         $this->selectedTenantId = $tenantId;
//          \Illuminate\Support\Facades\Log::debug('CategoryDistributionChart: Received tenantSelected', ['tenantId' => $tenantId]);
//     }

//     public function getHeading(): ?string
//     {
//         if ($this->selectedTenantId) {
//             $tenant = Tenant::find($this->selectedTenantId);
//             return 'Category Distribution for ' . ($tenant?->name ?? 'Unknown Tenant');
//         }
//         return 'Category Distribution (Select Tenant)';
//     }

//     protected function getType(): string
//     {
//         return 'pie';
//     }

//     protected function getData(): array
//     {
//         if (!$this->selectedTenantId) {
//             return ['datasets' => [], 'labels' => []];
//         }

//         // Correct query for categories within the selected tenant
//         $data = Artwork::query()
//             ->where('artworks.tenant_id', $this->selectedTenantId)
//             ->join('categories', 'artworks.category_id', '=', 'categories.id')
//             ->where('categories.tenant_id', $this->selectedTenantId) // Ensure category also belongs to the tenant
//             ->select(
//                 'categories.name as category_name',
//                 DB::raw('COUNT(artworks.id) as artwork_count')
//             )
//             ->groupBy('categories.id', 'categories.name')
//             ->orderBy('categories.name')
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