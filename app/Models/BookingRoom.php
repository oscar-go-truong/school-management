<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'class_id',
        'request_id',
        'status'
    ];
}
