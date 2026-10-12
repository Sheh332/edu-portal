<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'code',
        'section',
        'created_by',
    ];

    /** Students enrolled in this classroom. */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'classroom_id');
    }

    /** Teachers assigned to teach this classroom. */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_teacher', 'classroom_id', 'user_id')
            ->withTimestamps();
    }

    /** Creator of the class (usually a teacher/admin). */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
