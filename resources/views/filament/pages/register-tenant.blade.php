<x-filament-panels::page.simple> {{-- Use SimplePage layout --}}

    {{-- Optional: Add a heading --}}
    <x-slot name="heading">
        Register New Organization
    </x-slot>

    {{-- Render the form defined in the Page class --}}
    <form wire:submit="register">
        {{ $this->form }}

        {{-- Render the register action button --}}
        <div class="mt-6">
            {{ $this->registerAction }}
        </div>
    </form>

</x-filament-panels::page.simple>