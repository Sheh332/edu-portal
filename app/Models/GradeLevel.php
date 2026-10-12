<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    protected $fillable = ['grade', 'min_days', 'max_days', 'description'];

    /** Resolve the grade label for a given number of present days. */
    public static function forDays(int $days): ?self
    {
        return static::where('min_days', '<=', $days)
            ->where('max_days', '>=', $days)
            ->orderByDesc('min_days')
            ->first();
    }
}
