<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFacultyAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // SuperAdmin memiliki akses penuh
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Admin harus memiliki akses ke fakultas
        if ($user->role === 'admin') {
            $facultyId = $request->route('faculty_id') ??
                         $request->route('facultyId') ??
                         $request->input('faculty_id');

            if ($facultyId) {
                $hasAccess = $user->adminFaculties()
                    ->where('faculty_id', $facultyId)
                    ->where('status', 'active')
                    ->exists();

                if (!$hasAccess) {
                    abort(403, 'Anda tidak memiliki akses ke fakultas ini.');
                }
            } else {
                $hasAccess = $user->adminFaculties()
                    ->where('status', 'active')
                    ->exists();

                if (!$hasAccess) {
                    abort(403, 'Anda tidak memiliki akses ke fakultas manapun.');
                }
            }

            return $next($request);
        }

        abort(403, 'Akses tidak diizinkan.');
    }
}
