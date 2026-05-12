<x-filament-widgets::widget>
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Quick actions</p>
        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
            <a href="{{ \App\Filament\Pages\OrderWizardPage::getUrl() }}"
               class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-3 text-sm font-bold text-white shadow hover:bg-primary-500">
                + New order
            </a>
            <a href="{{ \App\Filament\Resources\QuoteResource::getUrl('create') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                Create quote
            </a>
            <a href="{{ \App\Filament\Resources\InvoiceResource::getUrl('create') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                Record payment
            </a>
            <a href="{{ \App\Filament\Pages\DealKanbanBoard::getUrl() }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                View pipeline
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
