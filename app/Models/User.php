<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'fullname',
        'status',
        'phone',
        'mobile', 
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    public function scopeInRole($query, $input)
    {
        if (isset($input['role']) && count($input['role']) > 0) {
            return $query->orWhereHas('roles', function ($query) use($input) {
                $query->whereIn('name', $input['role']);
            });
        }
        return $query;
    }

    public function scopeStatus($query, $input)
    {
        if (isset($input['status'])) {
            return $query->where('status', $input['status']);
        }
        return $query;
    }

    public function requestUser(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function approveUser(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function coures(): HasMany
    {
        return $this->HasMany(Course::class);
    }

    public function exam(): hasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function userCourse(): HasMany
    {
        return $this->hasMany(UserCourse::class);
    }

    public function eventParticipants() : HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }
}
