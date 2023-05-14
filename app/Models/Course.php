<?php

namespace App\Models;

use App\Enums\UserRoleContants;
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


    public function bookingRoom(): HasMany
    {
        return $this->HasMany(BookingRoom::class);
    }

    public function exam(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, "subject_id");
    }

    public function scopeYear($query, $input)
    {
        if($input['year']!==null)
            return $query->whereRaw('Year(created_at) = '.$input['year']);
        else
            return $query;
    }
    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function userCourse() : HasMany
    {
        return $this->hasMany(UserCourse::class); 
    }

    public function teachers()
    {
        return $this->hasMany(UserCourse::class)->whereHas('user', function ($query) {
            $query->role('teacher');
        });
    }

    public function students()
    {
        return $this->hasMany(UserCourse::class)->whereHas('user', function ($query) {
            $query->role('student');
        });
    }

    public function schedules() : HasMany
    {
        return $this->hasMany(Schedule::class);
    }

}
