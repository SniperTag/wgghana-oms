<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and needs to change their password
         $user = Auth::user();

    // Allow password change route without redirect loop
    if ($user && !$user->password_changed && !$request->is('passwords/change', 'passwords/change/*')) {
        return redirect()->route('password.change');
    }
     return $next($request);
}
}
