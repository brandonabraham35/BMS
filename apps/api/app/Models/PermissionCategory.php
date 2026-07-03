<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'slug', 'description'];

    public function groups()
    {
        return $this->hasMany(PermissionGroup::class, 'category_id');
    }
}
