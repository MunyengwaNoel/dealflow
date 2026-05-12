<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Deal extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'title',
        'description',
        'stage',
        'priority',
        'value',
        'expected_close_date',
        'actual_close_date',
        'lost_reason',
        'notes',
        'assigned_to',
    ];

    protected $casts = [
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
        'value' => 'decimal:2',
        'stage' => \App\Enums\DealStage::class,
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function activities()
    {
        return $this->hasMany(DealActivity::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
