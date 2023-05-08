<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRoomRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "course_id",
        "room_id",
        "booking_date_start",
        "booking_date_finish"
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
