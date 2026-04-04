<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        
        $user = Auth::user();
        
        // Check if user has any of the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized. Only administrators can access this page.');
    }
}