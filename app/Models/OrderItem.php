<?php

namespace App\Models;

use App\Enums\OrderItemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'service_template_id',
        'service_type',
        'name',
        'description',
        'quantity',
        'unit_cost',
        'unit_price',
        'line_total',
        'line_profit',
        'metadata',
        'status',
        'delivery_date',
        'completed_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'line_profit' => 'decimal:2',
        'metadata' => 'array',
        'delivery_date' => 'date',
        'completed_at' => 'datetime',
        'status' => OrderItemStatus::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function serviceTemplate(): BelongsTo
    {
        return $this->belongsTo(ServiceTemplate::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'order_item_id');
    }
}
