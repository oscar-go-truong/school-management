<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'course_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, "course_id");
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function scopeCourse($query, $courseId)
    {
        if($courseId)
            return $query->where('course_id', $courseId);
        return $query;
    }

    public function scopeYear($query, $year)
    {
        if($year)
            return $query->whereHas('course',function($query) use($year){
                return $query->whereRaw('Year(created_at) = '.$year);
            });
        return $query;
    }
}
