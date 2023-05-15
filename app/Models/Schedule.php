<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'finish_time',
        'weekday',
        'room_id'
    ];
    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function room() : BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
