<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RejectsDemoWrites;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends ApiController
{
    use RejectsDemoWrites;

    public function index(Request $request)
    {
        $q = Deal::query()->with(['client', 'assignedTo']);
        if ($stage = $request->string('stage')->toString()) {
            $q->where('stage', $stage);
        }
        if ($cid = $request->integer('client_id')) {
            $q->where('client_id', $cid);
        }

        $perPage = min(max((int) $request->get('per_page', 20), 1), 100);
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stage' => 'nullable|in:lead,potential,quoted,negotiation,won,lost',
            'priority' => 'nullable|in:hot,warm,cold,dead',
            'value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
            'actual_close_date' => 'nullable|date',
            'lost_reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $deal = Deal::query()->create($data);

        return $this->successResponse($deal, 'Created.', null, 201);
    }

    public function show(Deal $deal)
    {
        return $this->successResponse($deal->load(['client', 'assignedTo', 'activities']), 'OK');
    }

    public function update(Request $request, Deal $deal)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'stage' => 'nullable|in:lead,potential,quoted,negotiation,won,lost',
            'priority' => 'nullable|in:hot,warm,cold,dead',
            'value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
            'actual_close_date' => 'nullable|date',
            'lost_reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $deal->update($data);

        return $this->successResponse($deal->fresh(), 'Updated.');
    }

    public function destroy(Request $request, Deal $deal)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $deal->delete();

        return $this->successResponse((object) [], 'Deleted.');
    }
}
