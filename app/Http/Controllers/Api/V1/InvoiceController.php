<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RejectsDemoWrites;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends ApiController
{
    use RejectsDemoWrites;

    public function __construct(
        protected InvoiceService $invoices
    ) {
    }

    public function index(Request $request)
    {
        $q = Invoice::query()->with(['client']);
        if ($cid = $request->integer('client_id')) {
            $q->where('client_id', $cid);
        }
        if ($status = $request->string('status')->toString()) {
            $q->where('status', $status);
        }

        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $pag = $q->orderByDesc('id')->paginate($perPage);

        return $this->successResponse($pag->items(), 'OK', [
            'pagination' => [
                'current_page' => $pag->currentPage(),
                'last_page' => $pag->lastPage(),
                'per_page' => $pag->perPage(),
                'total' => $pag->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quote_id' => 'nullable|exists:quotes,id',
            'status' => 'nullable|in:draft,sent,partial,paid,overdue,cancelled',
            'subtotal' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|in:cash,ecocash,zipit,bank_transfer,other',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $data) {
            $data['created_by'] = $request->user()->id;
            $data['amount_paid'] = 0;
            $data['amount_due'] = $data['total'];
            $invoice = Invoice::query()->create($data);
            $this->invoices->syncTotals($invoice);

            return $this->successResponse($invoice->fresh(), 'Created.', null, 201);
        });
    }

    public function show(Invoice $invoice)
    {
        return $this->successResponse($invoice->load(['client', 'quote', 'payments.recordedBy']), 'OK');
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'status' => 'nullable|in:draft,sent,partial,paid,overdue,cancelled',
            'subtotal' => 'sometimes|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total' => 'sometimes|numeric|min:0',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|in:cash,ecocash,zipit,bank_transfer,other',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($data);
        $this->invoices->syncTotals($invoice->fresh());

        return $this->successResponse($invoice->fresh(), 'Updated.');
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $invoice->payments()->delete();
        $invoice->delete();

        return $this->successResponse((object) [], 'Deleted.');
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,ecocash,zipit,bank_transfer,other',
            'payment_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $payment = $this->invoices->recordPayment($invoice, $data, $request->user()->id);

        return $this->successResponse($payment->load('invoice'), 'Payment recorded.', null, 201);
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['client', 'tenant', 'payments']);

        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);

        return $pdf->stream('invoice-'.$invoice->invoice_number.'.pdf');
    }
}
