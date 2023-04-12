<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCourse extends Model
{
    protected $table = "user_course";
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'course_id',
        'status'
    ];
  
}
