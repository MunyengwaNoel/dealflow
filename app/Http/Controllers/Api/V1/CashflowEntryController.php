<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RejectsDemoWrites;
use App\Models\CashflowEntry;
use Illuminate\Http\Request;

class CashflowEntryController extends ApiController
{
    use RejectsDemoWrites;

    public function index(Request $request)
    {
        $q = CashflowEntry::query()->with(['client', 'invoice']);
        if ($type = $request->string('entry_type')->toString()) {
            $q->where('entry_type', $type);
        }
        if ($from = $request->date('from')) {
            $q->whereDate('entry_date', '>=', $from);
        }
        if ($to = $request->date('to')) {
            $q->whereDate('entry_date', '<=', $to);
        }

        $perPage = min(max((int) $request->get('per_page', 30), 1), 200);
        $pag = $q->orderByDesc('entry_date')->paginate($perPage);

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
            'entry_type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:120',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,ecocash,zipit,bank_transfer,other',
            'entry_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
        ]);

        $data['recorded_by'] = $request->user()->id;
        $entry = CashflowEntry::query()->create($data);

        return $this->successResponse($entry, 'Created.', null, 201);
    }

    public function show(CashflowEntry $cashflowEntry)
    {
        return $this->successResponse($cashflowEntry->load(['client', 'invoice', 'recordedBy']), 'OK');
    }

    public function update(Request $request, CashflowEntry $cashflowEntry)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'entry_type' => 'sometimes|in:income,expense',
            'category' => 'nullable|string|max:120',
            'description' => 'nullable|string|max:500',
            'amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'nullable|in:cash,ecocash,zipit,bank_transfer,other',
            'entry_date' => 'sometimes|date',
            'reference' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
        ]);

        $cashflowEntry->update($data);

        return $this->successResponse($cashflowEntry->fresh(), 'Updated.');
    }

    public function destroy(Request $request, CashflowEntry $cashflowEntry)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $cashflowEntry->delete();

        return $this->successResponse((object) [], 'Deleted.');
    }
}
