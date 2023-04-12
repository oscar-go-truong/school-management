<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

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
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    public function isAdministrator() : bool {
       return $this->role === UserRole::Adminstrator;
    }

    public function isTeacher() : bool{
        return $this->role === UserRole::Teacher;
    }

    public function isStudent() : bool {
       return $this->role === UserRole::Student;
    }

    public function request_user() : HasMany {
        return $this->hasMany(Request::class);
    }

    public function approve_user() : HasMany {
        return $this->hasMany(Request::class);
    }

    public function coures() : HasMany {
        return $this->HasMany(Course::class);
    }

    public function exam() : hasMany {
        return $this->hasMany(Exam::class);
    }
}
