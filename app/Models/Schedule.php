<?php

namespace App\Models;

use App\Enums\StatusTypeContants;
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
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function availableRoomsToChange()
    {
        $startTime = $this->start_time;
        $finishTime = $this->finish_time;
        $weekday = $this->weekday;
        $rooms = Room::select('id', 'name')->whereDoesntHave('schedules', function ($query) use ($startTime, $finishTime, $weekday) {
            $query->whereHas('course', function ($query) {
                $query->where('status', StatusTypeContants::ACTIVE);
            })->whereRaw('((start_time <="' . $startTime . '" and "' . $startTime . '"<= finish_time) or (start_time <= "' . $finishTime . '" and "' . $finishTime . '" <= finish_time) or (start_time >= "' . $startTime . '" and "' . $finishTime . '" >= finish_time))')->where('weekday', $weekday);
        })->orWhere('id', $this->room_id)->get();
        return $rooms;
    }
}
