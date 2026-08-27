<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses halaman berdasarkan role user yang login.
 * Sesuai PRD: "Mekanik tidak bisa melihat laporan keuangan Admin."
 *
 * Pemakaian di routes: ->middleware('role:admin') atau ->middleware('role:admin,mekanik')
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
