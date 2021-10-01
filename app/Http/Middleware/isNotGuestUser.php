<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isNotGuestUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->is_guest_user) {
            return redirect()->back();
        }
        return $next($request);
    }
}
