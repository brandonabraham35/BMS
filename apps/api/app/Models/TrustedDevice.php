<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrustedDevice extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id',
        'device_id',
        'name',
        'browser',
        'os',
        'ip_address',
        'last_active_at',
        'is_trusted',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
        'is_trusted' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
