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

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function teachers()
    {
        return $this->hasMany(UserCourse::class)->where('role', UserRoleContants::TEACHER);
    }

    public function students()
    {
        return $this->hasMany(UserCourse::class)->where('role', UserRoleContants::STUDENT);
    }
}
