<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PriceCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderQuotePreviewController extends Controller
{
    public function print(Request $request, Order $order): View
    {
        return view('pdf.order-quote-preview-print', $this->previewPayload($order) + [
            'autoprint' => $request->boolean('autoprint'),
        ]);
    }

    public function pdf(Order $order): Response
    {
        $data = $this->previewPayload($order);

        $pdf = Pdf::loadView('pdf.order-quote-preview', $data);

        $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $order->order_number) ?: 'order';

        return $pdf->download('quote-preview-'.$safe.'.pdf');
    }

    public function csv(Order $order): StreamedResponse
    {
        $p = $this->previewPayload($order);
        $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $order->order_number) ?: 'order';

        return response()->streamDownload(function () use ($p): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Order', 'Field', 'Value']);
            fputcsv($out, ['', 'Order number', $p['order']->order_number]);
            fputcsv($out, ['', 'Client', $p['client']?->name ?? '']);
            fputcsv($out, ['', 'Subtotal', (string) $p['subtotal']]);
            fputcsv($out, ['', 'Internal cost', (string) $p['total_cost']]);
            fputcsv($out, ['', 'Profit', (string) $p['profit']]);
            fputcsv($out, ['', 'Payment terms', (string) $p['payment_terms']]);
            fputcsv($out, ['', 'Message', (string) $p['personal_message']]);
            fputcsv($out, []);
            fputcsv($out, ['Line', 'Quantity', 'Unit price', 'Line total']);
            foreach ($p['lines'] as $line) {
                fputcsv($out, [
                    $line['name'],
                    (string) $line['quantity'],
                    (string) $line['unit_price'],
                    (string) $line['line_total'],
                ]);
            }
            fclose($out);
        }, 'quote-preview-'.$safe.'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function xlsx(Order $order): StreamedResponse
    {
        $p = $this->previewPayload($order);
        $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $order->order_number) ?: 'order';

        return response()->streamDownload(function () use ($p): void {
            $tmp = tempnam(sys_get_temp_dir(), 'qprev-xlsx-');
            if ($tmp === false) {
                return;
            }

            $writer = new XlsxWriter;
            $writer->openToFile($tmp);
            $writer->getCurrentSheet()->setName('Preview');

            $writer->addRow(Row::fromValues(['Field', 'Value']));
            $writer->addRow(Row::fromValues(['Order number', $p['order']->order_number]));
            $writer->addRow(Row::fromValues(['Client', $p['client']?->name ?? '']));
            $writer->addRow(Row::fromValues(['Subtotal', (float) $p['subtotal']]));
            $writer->addRow(Row::fromValues(['Internal cost', (float) $p['total_cost']]));
            $writer->addRow(Row::fromValues(['Profit', (float) $p['profit']]));
            $writer->addRow(Row::fromValues(['Payment terms', (string) $p['payment_terms']]));
            $writer->addRow(Row::fromValues(['Message', (string) $p['personal_message']]));

            $writer->addRow(Row::fromValues([]));
            $writer->addRow(Row::fromValues(['Line', 'Quantity', 'Unit price', 'Line total']));
            foreach ($p['lines'] as $line) {
                $writer->addRow(Row::fromValues([
                    $line['name'],
                    (float) $line['quantity'],
                    (float) $line['unit_price'],
                    (float) $line['line_total'],
                ]));
            }

            $writer->close();

            readfile($tmp);
            unlink($tmp);
        }, 'quote-preview-'.$safe.'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function previewPayload(Order $order): array
    {
        $this->authorizeOrder($order);

        $order->loadMissing(['client', 'tenant']);
        $state = $order->wizard_state ?? [];
        $calc = app(PriceCalculator::class);
        $lines = $calc->linesFromWizardState($state);
        $computed = $calc->compute($lines);

        $review = $state['review'] ?? [];
        $paymentKey = $review['payment_terms'] ?? 'deposit';
        $paymentTerms = match ($paymentKey) {
            'full' => __('Full payment upfront'),
            'installments' => __('Three equal installments'),
            default => __('50% deposit, 50% on delivery'),
        };

        $personalMessage = (string) ($review['personal_message'] ?? '');

        return [
            'order' => $order,
            'client' => $order->client,
            'tenant' => $order->tenant,
            'lines' => $computed['lines'],
            'subtotal' => $computed['subtotal'],
            'total_cost' => $computed['total_cost'],
            'profit' => $computed['profit'],
            'margin_percent' => $computed['margin_percent'],
            'payment_terms' => is_string($paymentTerms) ? $paymentTerms : (string) $paymentTerms,
            'personal_message' => $personalMessage,
        ];
    }

    private function authorizeOrder(Order $order): void
    {
        $user = auth()->user();
        abort_unless($user && (int) $user->tenant_id === (int) $order->tenant_id, 403);
    }
}
