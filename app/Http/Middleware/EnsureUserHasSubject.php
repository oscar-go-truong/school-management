<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleNameContants;
use App\Models\Subject;
use App\Models\UserCourse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasSubject
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
        $user = Auth::user();
        $id = $request->route('id');
        if(Subject::where('id',$id)->count() === 0)
            return abort(404);
        $isHasSubject = $user->hasRole(UserRoleNameContants::ADMIN) || UserCourse::whereHas('course', function($query) use($id){
            $query->where('subject_id', $id);
        })->where('user_id',$user->id)->count() !== 0;
        if($isHasSubject)
            return $next($request);
        return abort(404);
    }
}
