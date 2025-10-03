<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TutorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin or tutor
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isTutor())) {
            return $next($request);
        }

        // Check if student is authenticated (students can't access tutor areas)
        if (auth('student')->check()) {
            abort(403, 'Access denied. Tutor access required.');
        }

        // Not authenticated at all
        return redirect()->route('login')->with('error', 'Tutor access required.');
    }
}
