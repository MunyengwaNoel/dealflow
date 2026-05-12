<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    public static function nextQuoteNumber(int $tenantId): string
    {
        $prefix = 'Q'.$tenantId.'-';
        $last = Quote::query()
            ->where('tenant_id', $tenantId)
            ->where('quote_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('quote_number');
        $n = 1;
        if ($last && strlen($last) > strlen($prefix)) {
            $suffix = substr($last, strlen($prefix));
            if (ctype_digit($suffix)) {
                $n = ((int) $suffix) + 1;
            }
        }

        return $prefix.str_pad((string) $n, 5, '0', STR_PAD_LEFT);
    }

    public function recalculate(Quote $quote): void
    {
        $quote->load('items');
        $subtotal = 0.0;
        $profit = 0.0;
        foreach ($quote->items as $item) {
            /** @var QuoteItem $item */
            $line = (float) $item->line_total;
            if ($line <= 0) {
                $line = (float) $item->sell_price * (float) $item->quantity;
            }
            $subtotal += $line;
            $profit += ((float) $item->sell_price - (float) $item->cost_price) * (float) $item->quantity;
        }

        $attrs = $quote->getAttributes();
        $fixedDiscount = (float) ($attrs['discount_amount'] ?? 0);
        $percent = (float) ($attrs['discount_percent'] ?? 0);
        $discount = $fixedDiscount + round($subtotal * ($percent / 100), 2);

        $total = max(0, round($subtotal - $discount, 2));

        $quote->forceFill([
            'subtotal' => round($subtotal, 2),
            'total' => $total,
            'profit_total' => round($profit, 2),
        ])->saveQuietly();
    }

    public function convertToInvoice(Quote $quote, ?int $userId = null): Invoice
    {
        if ($quote->invoice) {
            return $quote->invoice;
        }

        return DB::transaction(function () use ($quote, $userId) {
            $this->recalculate($quote);
            $quote->refresh();

            $invoice = Invoice::query()->create([
                'tenant_id' => $quote->tenant_id,
                'client_id' => $quote->client_id,
                'quote_id' => $quote->id,
                'invoice_number' => InvoiceService::nextInvoiceNumber($quote->tenant_id),
                'status' => 'draft',
                'subtotal' => $quote->subtotal,
                'discount_amount' => $quote->discount_amount,
                'total' => $quote->total,
                'amount_paid' => 0,
                'amount_due' => $quote->total,
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays(14)->toDateString(),
                'notes' => $quote->notes,
                'created_by' => $userId ?? auth()->id(),
            ]);

            $quote->update(['status' => 'invoiced']);

            return $invoice;
        });
    }
}
