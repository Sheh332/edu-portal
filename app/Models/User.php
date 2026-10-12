<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'email', 'role', 'password', 'phone', 'profile_picture', 'classroom_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Student's enrolled classroom. */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /** Classes taught by the teacher. */
    public function taughtClasses(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_teacher', 'user_id', 'classroom_id')
            ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'teacher']) || $this->hasRole('admin') || $this->hasRole('teacher');
    }

    public function isTeacher(): bool
    {
        return in_array($this->role, ['teacher', 'admin']) || $this->hasRole('teacher') || $this->hasRole('admin');
    }

    public function isStudent(): bool
    {
        return $this->role === 'student' && !$this->isTeacher();
    }

    /** Days marked present within an optional inclusive date range. */
    public function presentDays($from = null, $to = null): int
    {
        return $this->attendances()
            ->when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->count();
    }
}
