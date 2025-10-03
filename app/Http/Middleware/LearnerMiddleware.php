<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LearnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if student is authenticated and is a learner
        if (auth('student')->check() && auth('student')->user()->isLearner()) {
            return $next($request);
        }

        // Check if regular user is authenticated (they can't access learner areas)
        if (auth()->check()) {
            abort(403, 'Access denied. Student access required.');
        }

        // Not authenticated at all
        return redirect()->route('login')->with('error', 'Student access required.');
    }
}
