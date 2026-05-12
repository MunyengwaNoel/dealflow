<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTemplate extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'template_code',
        'name',
        'description',
        'category',
        'cost_price',
        'sell_price',
        'pricing_structure',
        'demo_links',
        'required_documents',
        'deliverables',
        'timeline_days',
        'automation_rules',
        'status',
        'version_label',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'pricing_structure' => 'array',
        'demo_links' => 'array',
        'required_documents' => 'array',
        'deliverables' => 'array',
        'automation_rules' => 'array',
    ];

    public function versions()
    {
        return $this->hasMany(TemplateVersion::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActiveCatalog($query)
    {
        return $query->where('status', 'active')->where('is_active', true);
    }
}
