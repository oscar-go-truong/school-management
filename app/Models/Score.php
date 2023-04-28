<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Score extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'exam_id',
        'student_id',
        'total'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "student_id");
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, "exam_id");
    }
}
