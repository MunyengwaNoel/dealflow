<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Quote extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'deal_id',
        'service_template_id',
        'quote_number',
        'status',
        'subtotal',
        'discount_amount',
        'discount_percent',
        'tax_amount',
        'total',
        'profit_total',
        'notes',
        'payment_terms',
        'validity_days',
        'valid_until',
        'demo_links',
        'portal_token',
        'pdf_path',
        'sent_at',
        'viewed_at',
        'accepted_at',
        'declined_at',
        'decline_reason',
        'created_by',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'profit_total' => 'decimal:2',
        'demo_links' => 'array',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function serviceTemplate()
    {
        return $this->belongsTo(ServiceTemplate::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function analytics()
    {
        return $this->hasMany(QuoteAnalytic::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function portalUrl(): ?string
    {
        if (! $this->portal_token) {
            return null;
        }

        return URL::route('quote.portal', ['token' => $this->portal_token]);
    }
}
