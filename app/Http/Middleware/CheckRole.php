<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // kalau role user gak sesuai, arahkan ke tempatnya masing-masing
        if (Auth::user()->role !== $role) {
            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard');
            } else {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
