<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateVersion extends Model
{
    protected $fillable = [
        'service_template_id',
        'version_number',
        'changes_summary',
        'template_data',
        'created_by',
    ];

    protected $casts = [
        'template_data' => 'array',
    ];

    public function serviceTemplate(): BelongsTo
    {
        return $this->belongsTo(ServiceTemplate::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
