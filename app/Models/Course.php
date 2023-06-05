<?php

namespace App\Models;

use App\Enums\UserRoleNameContants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'descriptions',
        'status',
        'owner_id',
        'start_time',
        'finish_time',
        'weekday',
        'subject_id'
    ];


    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopeYear($query, $year)
    {
        if ($year) {
            return $query->whereRaw('Year(created_at) = ' . $year);
        }
        return $query;
    }
    public function scopeSubject($query, $subjectId)
    {
        if ($subjectId) {
            return $query->where('subject_id', $subjectId);
        }
        return $query;
    }
    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function userCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class);
    }

    public function teachers()
    {
        return $this->hasMany(UserCourse::class)->whereHas('user', function ($query) {
            $query->role(UserRoleNameContants::TEACHER);
        });
    }

    public function students()
    {
        return $this->hasMany(UserCourse::class)->whereHas('user', function ($query) {
            $query->role(UserRoleNameContants::STUDENT);
        });
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
