<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClass extends Model
{
    protected  $table = "user_class";
    use HasFactory;
    protected $fillable = [
        'user_id',
        'class_id',
        'status'
    ];
  
}
