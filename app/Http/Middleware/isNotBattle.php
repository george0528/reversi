<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isNotBattle
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
        if(isset($user->room) && isset($user->room_id)) {
            if($user->room->is_battle == 1) {
                return redirect(route('index'));
            }
        }
        return $next($request);
    }
}
