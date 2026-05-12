<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteAnalytic extends Model
{
    protected $fillable = [
        'quote_id',
        'event_type',
        'event_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
