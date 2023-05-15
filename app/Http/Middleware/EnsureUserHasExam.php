<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleNameContants;
use App\Models\Exam;
use App\Models\UserCourse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasExam
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
        if(Exam::where('id',$id)->count() === 0)
            return abort(404);
        $exam = Exam::find($id);
        $isHasExam =Auth::user()->hasRole(UserRoleNameContants::ADMIN) ||  UserCourse::where('user_id', $user->id)->where('course_id', $exam->course_id)->count() !==0;
        if($isHasExam)
            return $next($request);
        return abort(404);
    }
}
