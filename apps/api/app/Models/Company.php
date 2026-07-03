<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, HasUuid, SoftDeletes, Searchable;

    protected $fillable = [
        'workspace_id',
        'parent_company_id',
        'name',
        'slug',
        'legal_name',
        'registration_number',
        'tax_number',
        'vat_number',
        'business_type',
        'industry',
        'country',
        'currency',
        'timezone',
        'language',
        'fiscal_year',
        'business_hours',
        'status',
        'date_founded',
        'description',
        'email',
        'phone',
        'address',
        'website',
        'logo',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'business_hours' => 'array',
        'is_active' => 'boolean',
        'date_founded' => 'date',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Company::class, 'parent_company_id');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
