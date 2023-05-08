<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwitchCourseRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "old_course_id",
        "new_course_id"
    ];

    public function oldCourse() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function newCourse() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
