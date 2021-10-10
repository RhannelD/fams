<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticated
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
        if ( Auth::check() ) {
            if ( Auth::user()->is_admin() ) {
                return redirect()->route('dashboard');
            } elseif ( Auth::user()->is_officer() ) {
                return redirect()->route('scholarship');
            } elseif ( Auth::user()->is_scholar() ) {
                return redirect()->route('scholar.scholarship');
            }
        }

        return $next($request);
    }
}
