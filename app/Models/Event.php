<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'description',
        'created_by',
        'room_id'
    ];
    public function eventParticipants() : HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function room() :BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function owner() : BelongsTo
    {
        Return $this->belongsTo(User::class,'created_by');
    }
}
