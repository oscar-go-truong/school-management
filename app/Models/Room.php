<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status'
    ];

    public function schedules() : HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function events() : HasMany
    {
        return $this->hasMany(Event::class);
    }
}

