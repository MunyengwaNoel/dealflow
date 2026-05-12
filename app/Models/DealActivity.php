<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealActivity extends Model
{
    protected $fillable = [
        'deal_id',
        'user_id',
        'activity_type',
        'description',
        'activity_date',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
