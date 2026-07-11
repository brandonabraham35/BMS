<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTransfer extends Model
{
    use HasFactory, HasUuid, SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
        'from_workspace_id',
        'from_company_id',
        'from_branch_id',
        'from_department_id',
        'to_workspace_id',
        'to_company_id',
        'to_branch_id',
        'to_department_id',
        'reason',
        'transferred_by',
        'created_by',
        'transferred_at',
        'effective_at',
        'previous_state',
        'new_state',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
        'effective_at' => 'datetime',
        'previous_state' => 'array',
        'new_state' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transferrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function fromWorkspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'from_workspace_id');
    }

    public function toWorkspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'to_workspace_id');
    }
}
