<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Client extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'trading_name',
        'email',
        'phone',
        'whatsapp',
        'address',
        'city',
        'country',
        'client_type',
        'status',
        'notes',
        'assigned_to',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
