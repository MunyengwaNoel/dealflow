<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RejectsDemoWrites;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends ApiController
{
    use RejectsDemoWrites;

    public function index(Request $request)
    {
        $q = Document::query()->with('client');
        if ($clientId = $request->integer('client_id')) {
            $q->where('client_id', $clientId);
        }
        if ($search = $request->string('search')->toString()) {
            $q->where('title', 'like', '%'.$search.'%');
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
            'document_type' => [
                'required',
                'string',
                'max:80',
                \Illuminate\Validation\Rule::in([
                    'certificate_of_incorporation',
                    'cr6',
                    'cr14',
                    'cr5',
                    'memorandum_articles',
                    'annual_return',
                    'tax_clearance',
                    'praz_certificate',
                    'nssa_certificate',
                    'business_plan',
                    'company_profile',
                    'domain_certificate',
                    'other',
                ]),
            ],
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'reminder_days_before' => 'nullable|integer|min:1|max:365',
            'notes' => 'nullable|string',
        ]);

        $tenant = app('tenant');
        $path = $request->file('file')->store('documents/'.$tenant->id, 'local');
        $data['file_path'] = $path;
        $data['file_size'] = $request->file('file')->getSize();
        $data['mime_type'] = $request->file('file')->getClientMimeType();
        $data['uploaded_by'] = $request->user()->id;
        unset($data['file']);

        $document = Document::query()->create($data);

        return $this->successResponse($document, 'Uploaded.', null, 201);
    }

    public function show(Document $document)
    {
        return $this->successResponse($document->load(['client', 'uploadedBy']), 'OK');
    }

    public function update(Request $request, Document $document)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        $data = $request->validate([
            'document_type' => [
                'sometimes',
                'string',
                'max:80',
                \Illuminate\Validation\Rule::in([
                    'certificate_of_incorporation',
                    'cr6',
                    'cr14',
                    'cr5',
                    'memorandum_articles',
                    'annual_return',
                    'tax_clearance',
                    'praz_certificate',
                    'nssa_certificate',
                    'business_plan',
                    'company_profile',
                    'domain_certificate',
                    'other',
                ]),
            ],
            'title' => 'sometimes|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'reminder_days_before' => 'nullable|integer|min:1|max:365',
            'notes' => 'nullable|string',
        ]);

        $document->update($data);

        return $this->successResponse($document->fresh(), 'Updated.');
    }

    public function destroy(Request $request, Document $document)
    {
        if ($r = $this->rejectIfDemo($request)) {
            return $r;
        }

        if ($document->file_path) {
            Storage::disk('local')->delete($document->file_path);
        }
        $document->delete();

        return $this->successResponse((object) [], 'Deleted.');
    }
}
