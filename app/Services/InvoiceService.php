<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public static function nextInvoiceNumber(int $tenantId): string
    {
        $prefix = 'INV'.$tenantId.'-';
        $last = Invoice::query()
            ->where('tenant_id', $tenantId)
            ->where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('invoice_number');
        $n = 1;
        if ($last && strlen($last) > strlen($prefix)) {
            $suffix = substr($last, strlen($prefix));
            if (ctype_digit($suffix)) {
                $n = ((int) $suffix) + 1;
            }
        }

        return $prefix.str_pad((string) $n, 5, '0', STR_PAD_LEFT);
    }

    public function syncTotals(Invoice $invoice): void
    {
        $paid = (float) $invoice->payments()->sum('amount');
        $total = (float) $invoice->total;
        $due = max(0, round($total - $paid, 2));

        $prev = $invoice->status;
        if ($due <= 0.009 && $total > 0) {
            $status = 'paid';
        } elseif ($paid > 0.009 && $due > 0.009) {
            $status = 'partial';
        } elseif ($paid <= 0.009 && in_array($prev, ['paid', 'partial'], true)) {
            $status = $invoice->quote_id ? 'sent' : 'draft';
        } else {
            $status = $prev;
        }

        $invoice->forceFill([
            'amount_paid' => round($paid, 2),
            'amount_due' => $due,
            'status' => $status,
            'paid_date' => $status === 'paid' ? ($invoice->paid_date ?? now()->toDateString()) : null,
        ])->saveQuietly();
    }

    public function recordPayment(Invoice $invoice, array $data, ?int $userId = null): InvoicePayment
    {
        return DB::transaction(function () use ($invoice, $data, $userId) {
            $payment = $invoice->payments()->create([
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'] ?? 'other',
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'reference' => $data['reference'] ?? null,
                'notes' => $data['notes'] ?? null,
                'recorded_by' => $userId ?? auth()->id(),
            ]);

            $this->syncTotals($invoice->fresh());

            return $payment;
        });
    }
}
