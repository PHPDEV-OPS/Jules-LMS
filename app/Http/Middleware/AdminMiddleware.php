<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is an admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Check if student is authenticated (students can't be admins)
        if (auth('student')->check()) {
            abort(403, 'Access denied. Admin access required.');
        }

        // Not authenticated at all
        return redirect()->route('login')->with('error', 'Admin access required.');
    }
}
