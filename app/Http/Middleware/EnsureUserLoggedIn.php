<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() && !session('logged_in')) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Silakan login terlebih dahulu.']);
        }

        return $next($request);
    }
}