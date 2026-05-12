<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id',
        'service_template_id',
        'name',
        'description',
        'cost_price',
        'sell_price',
        'quantity',
        'line_total',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function serviceTemplate()
    {
        return $this->belongsTo(ServiceTemplate::class, 'service_template_id');
    }
}
