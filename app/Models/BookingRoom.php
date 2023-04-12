<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRoom extends Model
{
    use HasFactory, 
        SoftDeletes;
    protected $fillable = [
        'room_id',
        'course_id',
        'request_id',
        'status'
    ];
}
