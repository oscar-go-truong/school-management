<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'user_request_id',
        'user_approve_id',
        'type',
        'status'
    ];

    public function booking_room() : BelongsTo {
        return $this->belongsTo(BookingRoom::class);
    }

    public function user_request() : BelongsTo {
        return $this->belongsTo(User::class, "user_request_id");
    }

    public function user_approve() : BelongsTo {
        return $this->belongsTo(User::class, 'user_approve_id');
    }

}
