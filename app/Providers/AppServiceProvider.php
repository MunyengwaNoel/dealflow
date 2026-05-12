<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Services\InvoiceService;
use App\Services\QuoteService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Must not use `instance('tenant', null)`: PHP `isset($instances['tenant'])` is false
        // when the value is null, so Laravel falls through and tries to resolve class "tenant".
        $this->app->bind('tenant', static fn () => null, true);
    }

    public function boot(): void
    {
        Quote::creating(function (Quote $quote): void {
            $tid = $quote->tenant_id;
            if (! $tid && app()->bound('tenant') && app('tenant')) {
                $tid = app('tenant')->id;
            }
            if (! $quote->quote_number && $tid) {
                $quote->quote_number = QuoteService::nextQuoteNumber($tid);
            }
            if (! $quote->created_by && auth()->check()) {
                $quote->created_by = auth()->id();
            }
        });

        Invoice::creating(function (Invoice $invoice): void {
            $tid = $invoice->tenant_id;
            if (! $tid && app()->bound('tenant') && app('tenant')) {
                $tid = app('tenant')->id;
            }
            if (! $invoice->invoice_number && $tid) {
                $invoice->invoice_number = InvoiceService::nextInvoiceNumber($tid);
            }
            if (! $invoice->created_by && auth()->check()) {
                $invoice->created_by = auth()->id();
            }
        });

        QuoteItem::saving(function (QuoteItem $item): void {
            $item->line_total = round((float) $item->sell_price * (float) $item->quantity, 2);
        });

        QuoteItem::saved(function (QuoteItem $item): void {
            if ($item->quote) {
                app(QuoteService::class)->recalculate($item->quote);
            }
        });

        QuoteItem::deleted(function (QuoteItem $item): void {
            if ($item->quote) {
                app(QuoteService::class)->recalculate($item->quote);
            }
        });
    }
}
