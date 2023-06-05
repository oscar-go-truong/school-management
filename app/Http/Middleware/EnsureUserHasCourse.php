<?php

namespace App\Http\Middleware;

use App\Enums\StatusTypeContants;
use App\Enums\UserRoleNameContants;
use App\Models\Course;
use App\Models\UserCourse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasCourse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');
        $user = Auth::user();
        if (Course::where('id', $id)->count() === 0) {
            return abort(404);
        }
        if ($user->hasRole(UserRoleNameContants::ADMIN) || UserCourse::where('user_id', $user->id)->where('course_id', $id)->where('status', StatusTypeContants::ACTIVE)->count() !== 0) {
            return $next($request, $id);
        }
        return abort(404);
    }
}
