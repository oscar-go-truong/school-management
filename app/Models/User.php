<?php

namespace App\Models;

use App\Enums\UserRoleNameContants;
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

    public function scopeInRole($query, $request)
    {
        if ($request->role && count($request->role) > 0) {
            return $query->orWhereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', $request->role);
            });
        }
        return $query;
    }

    public function scopeStatus($query, $request)
    {
        if ($request->status) {
            return $query->where('status', $request->status);
        }
        return $query;
    }

    public function notifications()
    {
        $query = Notification::orderBy('created_at', 'desc');

        if ($this->hasRole(UserRoleNameContants::ADMIN)) {
            $query = $query->WhereRaw('user_id is null or user_id=' . $this->id);
        } else {
            $query = $query->Where('user_id', $this->id);
        }
        return (object) [
            'notifications' => $query->offset(0)->limit(15)->get(),
            'unread_count' => $query->where('read_at', null)->count()
        ];
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

    public function exams(): hasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'student_id');
    }

    public function userCourse(): HasMany
    {
        return $this->hasMany(UserCourse::class);
    }

    public function eventParticipants(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }
}
