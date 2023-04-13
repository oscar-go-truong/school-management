<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRoom extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'room_id',
        'course_id',
        'request_id',
        'status'
    ];

    public function room() : BelongsTo {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function course() : BelongsTo {
        return $this->belongsTo(Course::class, 'courses_id');
    }

    public function request() : HasOne {
        return $this->hasOne(Request::class, 'request_id');
    }
}
