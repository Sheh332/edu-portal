<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $fillable = [
        'user_id', 'from_date', 'to_date', 'reason',
        'status', 'admin_comment', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /** Number of calendar days the leave spans (inclusive). */
    public function dayCount(): int
    {
        return $this->from_date->diffInDays($this->to_date) + 1;
    }
}
