<?php

namespace App\Http\Middleware;

use App\Enums\RequestStatusContants;
use Closure;
use Illuminate\Http\Request;

class EnsureRequestIsOpen
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
        if(\App\Models\Request::find($id)->status !== RequestStatusContants::PENDING)
        {
           return response()->json(['data' => null, 'message' => 'Request is closed. Please reload page again!']); 
        }
        return $next($request);
    }
}
