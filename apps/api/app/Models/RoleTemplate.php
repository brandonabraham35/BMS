<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleTemplate extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'slug', 'description', 'permissions', 'is_active'];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];
}
