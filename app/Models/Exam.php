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
        'status'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, "course_id");
    }

    public function score(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
