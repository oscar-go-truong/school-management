<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewScoreRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "exam_id"
    ];

    public function exam() : BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
