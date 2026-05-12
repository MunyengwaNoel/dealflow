<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RejectsDemoWrites;
use App\Models\ServiceTemplate;
use Illuminate\Http\Request;

class ServiceTemplateController extends ApiController
{
    use RejectsDemoWrites;

    public function index(Request $request)
    {
        $q = ServiceTemplate::query();
        if ($request->boolean('active_only')) {
            $q->where('is_active', true);
        }

        $perPage = min(max((int) $request->get('per_page', 50), 1), 100);
        $pag = $q->orderBy('name')->paginate($perPage);

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:120',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $template = ServiceTemplate::query()->create($data);

        return $this->successResponse($template, 'Created.', null, 201);
    }

    public function show(ServiceTemplate $serviceTemplate)
    {
        return $this->successResponse($serviceTemplate, 'OK');
    }

    public function update(Request $request, ServiceTemplate $serviceTemplate)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:120',
            'cost_price' => 'sometimes|numeric|min:0',
            'sell_price' => 'sometimes|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $serviceTemplate->update($data);

        return $this->successResponse($serviceTemplate->fresh(), 'Updated.');
    }

    public function destroy(Request $request, ServiceTemplate $serviceTemplate)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $serviceTemplate->delete();

        return $this->successResponse((object) [], 'Deleted.');
    }
}
