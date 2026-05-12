<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RejectsDemoWrites;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Services\QuoteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends ApiController
{
    use RejectsDemoWrites;

    public function __construct(
        protected QuoteService $quotes
    ) {
    }

    public function index(Request $request)
    {
        $q = Quote::query()->with(['client']);
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
            'status' => 'nullable|in:draft,sent,accepted,declined,invoiced',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.service_template_id' => 'nullable|exists:service_templates,id',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.cost_price' => 'required_with:items|numeric|min:0',
            'items.*.sell_price' => 'required_with:items|numeric|min:0',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01',
        ]);

        return DB::transaction(function () use ($request, $data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $data['created_by'] = $request->user()->id;
            $quote = Quote::query()->create($data);

            foreach ($items as $row) {
                QuoteItem::query()->create(array_merge($row, ['quote_id' => $quote->id]));
            }

            $this->quotes->recalculate($quote->fresh());

            return $this->successResponse($quote->load('items'), 'Created.', null, 201);
        });
    }

    public function show(Quote $quote)
    {
        return $this->successResponse($quote->load(['items.serviceTemplate', 'client', 'invoice']), 'OK');
    }

    public function update(Request $request, Quote $quote)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'status' => 'nullable|in:draft,sent,accepted,declined,invoiced',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:quote_items,id',
            'items.*.service_template_id' => 'nullable|exists:service_templates,id',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.cost_price' => 'required_with:items|numeric|min:0',
            'items.*.sell_price' => 'required_with:items|numeric|min:0',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01',
        ]);

        return DB::transaction(function () use ($request, $quote, $data) {
            $items = $data['items'] ?? null;
            unset($data['items']);
            $quote->update($data);

            if (is_array($items)) {
                $quote->items()->delete();
                foreach ($items as $row) {
                    unset($row['id']);
                    QuoteItem::query()->create(array_merge($row, ['quote_id' => $quote->id]));
                }
            }

            $this->quotes->recalculate($quote->fresh());

            return $this->successResponse($quote->load('items'), 'Updated.');
        });
    }

    public function destroy(Request $request, Quote $quote)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        if ($quote->invoice) {
            return $this->errorResponse('Cannot delete a quote that has been invoiced.', null, 422);
        }

        $quote->items()->delete();
        $quote->delete();

        return $this->successResponse((object) [], 'Deleted.');
    }

    public function convertToInvoice(Request $request, Quote $quote)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $invoice = $this->quotes->convertToInvoice($quote, $request->user()->id);

        return $this->successResponse($invoice->load('client'), 'Invoice created.');
    }

    public function pdf(Quote $quote)
    {
        $quote->load(['items', 'client', 'tenant']);

        $pdf = Pdf::loadView('pdf.quote', ['quote' => $quote]);

        return $pdf->stream('quote-'.$quote->quote_number.'.pdf');
    }
}
