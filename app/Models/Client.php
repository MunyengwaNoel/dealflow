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
        'bp_number',
        'tin_number',
        'registration_date',
        'registered_address',
        'physical_address',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'industry',
        'source',
        'lifetime_value',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'physical_address' => 'array',
        'lifetime_value' => 'decimal:2',
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
