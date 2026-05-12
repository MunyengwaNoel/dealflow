<x-filament-panels::page>
    @livewire(\App\Livewire\Orders\OrderWizard::class, [
        'resume' => request()->integer('order'),
        'prefillClient' => request()->integer('client'),
    ])
</x-filament-panels::page>
