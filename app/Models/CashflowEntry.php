<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class CashflowEntry extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'entry_type',
        'category',
        'description',
        'amount',
        'payment_method',
        'entry_date',
        'reference',
        'client_id',
        'invoice_id',
        'recorded_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
