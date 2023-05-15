<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleNameContants;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRequest
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
        if(\App\Models\Request::where('id', $id)->count() === 0)
            return abort(404);
        if($user->hasRole(UserRoleNameContants::ADMIN) || \App\Models\Request::where('user_request_id', $user->id)->where('id', $id)->count()!== 0)
            return $next($request, $id);
        return abort(404);
    }
}
