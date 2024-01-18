<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Determine the user's role or type and redirect accordingly
                $user = Auth::guard($guard)->user();

                if ($user->role == 'admin') {
                    return redirect('admin');
                } elseif ($user->role == 'pegawai') {
                    return redirect('pegawai/home');
                } elseif ($user->role == 'superadmin') {
                    return redirect('superadmin/home');
                }
            }
        }


        return $next($request);
    }
}
