<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceDocumentController extends Controller
{
    public function print(Request $request, Invoice $invoice): View
    {
        $invoice = $this->authorizedInvoice($invoice);

        return view('pdf.invoice-print', [
            'invoice' => $invoice,
            'autoprint' => $request->boolean('autoprint'),
        ]);
    }

    public function pdf(Invoice $invoice): Response
    {
        $invoice = $this->authorizedInvoice($invoice);

        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);

        return $pdf->download('invoice-'.$invoice->invoice_number.'.pdf');
    }

    public function csv(Invoice $invoice): StreamedResponse
    {
        $invoice = $this->authorizedInvoice($invoice);

        $filename = 'invoice-'.$invoice->invoice_number.'.csv';

        return response()->streamDownload(function () use ($invoice): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['Section', 'Field', 'Value']);
            fputcsv($out, ['Invoice', 'Number', $invoice->invoice_number]);
            fputcsv($out, ['Invoice', 'Client', $invoice->client?->name ?? '']);
            fputcsv($out, ['Invoice', 'Status', $invoice->status]);
            fputcsv($out, ['Invoice', 'Issue date', optional($invoice->issue_date)->toDateString() ?? '']);
            fputcsv($out, ['Invoice', 'Due date', optional($invoice->due_date)->toDateString() ?? '']);
            fputcsv($out, ['Invoice', 'Subtotal', (string) $invoice->subtotal]);
            fputcsv($out, ['Invoice', 'Discount', (string) $invoice->discount_amount]);
            fputcsv($out, ['Invoice', 'Total', (string) $invoice->total]);
            fputcsv($out, ['Invoice', 'Amount paid', (string) $invoice->amount_paid]);
            fputcsv($out, ['Invoice', 'Amount due', (string) $invoice->amount_due]);
            fputcsv($out, ['Invoice', 'Notes', (string) ($invoice->notes ?? '')]);
            foreach ($invoice->payments as $p) {
                fputcsv($out, [
                    'Payment',
                    optional($p->payment_date)->toDateString().' · '.$p->payment_method,
                    (string) $p->amount,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function xlsx(Invoice $invoice): StreamedResponse
    {
        $invoice = $this->authorizedInvoice($invoice);

        $filename = 'invoice-'.$invoice->invoice_number.'.xlsx';

        return response()->streamDownload(function () use ($invoice): void {
            $path = tempnam(sys_get_temp_dir(), 'inv-xlsx-');
            if ($path === false) {
                return;
            }

            $writer = new XlsxWriter;
            $writer->openToFile($path);
            $writer->getCurrentSheet()->setName('Invoice');

            $writer->addRow(Row::fromValues(['Field', 'Value']));
            $writer->addRow(Row::fromValues(['Invoice number', $invoice->invoice_number]));
            $writer->addRow(Row::fromValues(['Client', $invoice->client?->name ?? '']));
            $writer->addRow(Row::fromValues(['Status', $invoice->status]));
            $writer->addRow(Row::fromValues(['Issue date', optional($invoice->issue_date)->toDateString() ?? '']));
            $writer->addRow(Row::fromValues(['Due date', optional($invoice->due_date)->toDateString() ?? '']));
            $writer->addRow(Row::fromValues(['Subtotal', (float) $invoice->subtotal]));
            $writer->addRow(Row::fromValues(['Discount', (float) $invoice->discount_amount]));
            $writer->addRow(Row::fromValues(['Total', (float) $invoice->total]));
            $writer->addRow(Row::fromValues(['Amount paid', (float) $invoice->amount_paid]));
            $writer->addRow(Row::fromValues(['Amount due', (float) $invoice->amount_due]));
            $writer->addRow(Row::fromValues(['Notes', (string) ($invoice->notes ?? '')]));

            $writer->addRow(Row::fromValues([]));
            $writer->addRow(Row::fromValues(['Payments', '']));
            $writer->addRow(Row::fromValues(['Date', 'Method', 'Amount']));
            foreach ($invoice->payments as $p) {
                $writer->addRow(Row::fromValues([
                    optional($p->payment_date)->toDateString() ?? '',
                    (string) $p->payment_method,
                    (float) $p->amount,
                ]));
            }

            $writer->close();

            readfile($path);
            unlink($path);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function authorizedInvoice(Invoice $invoice): Invoice
    {
        $user = auth()->user();
        abort_unless($user && (int) $user->tenant_id === (int) $invoice->tenant_id, 403);

        return $invoice->load(['client', 'tenant', 'payments']);
    }
}
