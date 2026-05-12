<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RejectsDemoWrites;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends ApiController
{
    use RejectsDemoWrites;

    public function index(Request $request)
    {
        $q = Client::query();
        if ($search = $request->string('search')->toString()) {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        $sort = $request->get('sort', 'id');
        $dir = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        if (! in_array($sort, ['id', 'name', 'status', 'created_at'], true)) {
            $sort = 'id';
        }

        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $pag = $q->orderBy($sort, $dir)->paginate($perPage);

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

        $tenant = app('tenant');
        if (! $tenant?->canCreateClient()) {
            return $this->errorResponse('Client limit reached for your plan.', null, 403, [
                'upgrade_url' => url('/admin'),
            ]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'trading_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'client_type' => 'nullable|in:individual,business',
            'status' => 'nullable|in:active,inactive,prospect',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $client = Client::query()->create($data);

        return $this->successResponse($client, 'Created.', null, 201);
    }

    public function show(Client $client)
    {
        return $this->successResponse($client->load(['assignedTo']), 'OK');
    }

    public function update(Request $request, Client $client)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'trading_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'client_type' => 'nullable|in:individual,business',
            'status' => 'nullable|in:active,inactive,prospect',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $client->update($data);

        return $this->successResponse($client->fresh(), 'Updated.');
    }

    public function destroy(Request $request, Client $client)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $client->delete();

        return $this->successResponse((object) [], 'Deleted.');
    }
}
