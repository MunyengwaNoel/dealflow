<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceAlert extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'document_id',
        'alert_type',
        'alert_date',
        'expiry_date',
        'status',
        'notification_sent_at',
        'snoozed_until',
        'resolved_at',
    ];

    protected $casts = [
        'alert_date' => 'date',
        'expiry_date' => 'date',
        'snoozed_until' => 'date',
        'notification_sent_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
