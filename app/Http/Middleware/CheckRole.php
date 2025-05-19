<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan user terautentikasi di guard 'web' (klien) atau 'backpack' (admin)
        if (!auth()->check() && !backpack_auth()->check()) {
            return redirect()->route('login');
        }

        // Cek role
        $user = auth()->user() ?? backpack_user();
        if (!$user->hasRole($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
