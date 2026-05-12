<?php

namespace App\Jobs;

use App\Enums\DealPriority;
use App\Enums\DealStage;
use App\Enums\OrderItemStatus;
use App\Enums\OrderStatus;
use App\Models\Deal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Services\ComplianceTracker;
use App\Services\PriceCalculator;
use App\Services\QuoteNumberService;
use App\Services\QuoteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessOrderQuoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $orderId,
        public int $userId,
        public array $options = []
    ) {}

    public function handle(
        PriceCalculator $calculator,
        QuoteService $quoteService,
        QuoteNumberService $quoteNumbers,
        ComplianceTracker $compliance
    ): void {
        $order = Order::query()->with('client')->findOrFail($this->orderId);

        DB::transaction(function () use ($order, $calculator, $quoteService, $quoteNumbers, $compliance): void {
            $order->items()->delete();

            $lines = $calculator->linesFromWizardState($order->wizard_state ?? []);
            $computed = $calculator->compute($lines);

            foreach ($computed['lines'] as $row) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'service_template_id' => null,
                    'service_type' => $row['service_type'],
                    'name' => $row['name'],
                    'description' => null,
                    'quantity' => $row['quantity'],
                    'unit_cost' => $row['unit_cost'],
                    'unit_price' => $row['unit_price'],
                    'line_total' => $row['line_total'],
                    'line_profit' => $row['line_profit'],
                    'metadata' => ['service_type' => $row['service_type']],
                    'status' => OrderItemStatus::tryFrom($row['status']) ?? OrderItemStatus::Pending,
                ]);
            }

            $order->forceFill([
                'total_amount' => $computed['subtotal'],
                'total_cost' => $computed['total_cost'],
                'profit_amount' => $computed['profit'],
                'profit_margin' => $computed['margin_percent'],
            ])->save();

            $portalToken = Str::random(64);
            $paymentTerms = (string) ($this->options['payment_terms'] ?? $order->payment_terms ?? '50% deposit, 50% on delivery');
            $personalMessage = (string) ($this->options['personal_message'] ?? '');

            $quote = Quote::query()->create([
                'tenant_id' => $order->tenant_id,
                'client_id' => $order->client_id,
                'order_id' => $order->id,
                'quote_number' => $quoteNumbers->nextQtFormat((int) $order->tenant_id),
                'status' => 'sent',
                'subtotal' => $computed['subtotal'],
                'discount_amount' => 0,
                'discount_percent' => 0,
                'tax_amount' => 0,
                'total' => $computed['subtotal'],
                'profit_total' => $computed['profit'],
                'notes' => $personalMessage !== '' ? $personalMessage : ($order->notes ?? null),
                'payment_terms' => $paymentTerms,
                'validity_days' => 30,
                'valid_until' => now()->addDays(30)->toDateString(),
                'demo_links' => $order->wizard_state['demo_links'] ?? null,
                'portal_token' => $portalToken,
                'created_by' => $this->userId,
                'sent_at' => now(),
            ]);

            foreach ($computed['lines'] as $row) {
                QuoteItem::query()->create([
                    'quote_id' => $quote->id,
                    'service_template_id' => null,
                    'name' => $row['name'],
                    'description' => null,
                    'cost_price' => $row['unit_cost'],
                    'sell_price' => $row['unit_price'],
                    'quantity' => max(1, (int) round($row['quantity'])),
                    'line_total' => $row['line_total'],
                ]);
            }

            $quoteService->recalculate($quote->fresh());

            $pdf = Pdf::loadView('pdf.quote', ['quote' => $quote->load(['items', 'client', 'tenant'])]);
            $relative = 'quotes/'.$quote->tenant_id.'/'.$quote->id.'.pdf';
            Storage::disk('public')->put($relative, $pdf->output());
            $quote->forceFill(['pdf_path' => 'storage/'.$relative])->save();

            $deal = Deal::query()->create([
                'tenant_id' => $order->tenant_id,
                'client_id' => $order->client_id,
                'order_id' => $order->id,
                'title' => 'Quote '.$quote->quote_number.' — '.$order->services_summary,
                'description' => $order->notes,
                'stage' => DealStage::Quoted,
                'priority' => ((float) $computed['subtotal'] >= 500) ? DealPriority::Hot : DealPriority::Warm,
                'value' => $quote->total,
                'cost_total' => $computed['total_cost'],
                'profit' => $computed['profit'],
                'profit_margin_percent' => $computed['margin_percent'],
                'probability_percent' => 40,
                'source' => 'wizard',
                'expected_close_date' => now()->addDays(7)->toDateString(),
                'assigned_to' => $this->userId,
            ]);

            $quote->forceFill(['deal_id' => $deal->id])->save();

            $order->forceFill([
                'quote_id' => $quote->id,
                'status' => OrderStatus::Quoted,
            ])->save();

            $compliance->syncFromOrder($order->fresh());

            $client = $order->client;
            if ($client && ! empty($this->options['send_email']) && $client->email && Storage::disk('public')->exists($relative)) {
                $pdfPath = Storage::disk('public')->path($relative);
                Mail::send('emails.quote-sent', [
                    'quote' => $quote->fresh(['client', 'tenant']),
                    'portalUrl' => $quote->portalUrl(),
                ], function ($message) use ($client, $quote, $pdfPath) {
                    $message->to($client->email, $client->name)
                        ->subject('Your quote is ready — '.$quote->quote_number.' | '.config('app.name'));
                    $message->attach($pdfPath, ['as' => 'quote-'.$quote->quote_number.'.pdf']);
                });
            }
        });
    }
}
