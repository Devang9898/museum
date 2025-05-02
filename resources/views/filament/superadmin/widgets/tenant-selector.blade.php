<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            View Specific Tenant Data
        </x-slot>

        <div class="space-y-2">
            <label for="tenantSelect" class="text-sm font-medium text-gray-700 dark:text-gray-200">
                Select Tenant:
            </label>
            <x-filament::input.wrapper>
                {{-- wire:model.live links the select value to the $selectedTenantId property in the widget class --}}
                <x-filament::input.select wire:model.live="selectedTenantId" id="tenantSelect">
                    <option value="all">-- All Tenants (Global Stats) --</option>
                    {{-- Loop through the $tenantOptions property passed from the widget class --}}
                    @foreach ($tenantOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
            {{-- Conditionally show which tenant is selected --}}
            @if($selectedTenantId)
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Currently viewing data for tenant ID: {{ $selectedTenantId }}
                </p>
            @else
                 <p class="text-xs text-gray-500 dark:text-gray-400">
                    Currently viewing global data.
                </p>
            @endif
        </div>

    </x-filament::section>
</x-filament-widgets::widget>