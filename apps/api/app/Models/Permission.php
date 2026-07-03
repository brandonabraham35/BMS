<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory, HasUuid, Searchable;

    protected $fillable = [
        'group_id',
        'name',
        'slug',
        'group', // deprecated in favor of group_id but kept for compatibility
        'description',
        'metadata',
        'version',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function permissionGroup(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withPivot('metadata', 'expires_at')
            ->withTimestamps();
    }
}
