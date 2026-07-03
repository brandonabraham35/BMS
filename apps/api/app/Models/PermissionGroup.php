<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['category_id', 'name', 'slug', 'description'];

    public function category()
    {
        return $this->belongsTo(PermissionCategory::class, 'category_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'group_id');
    }
}
