<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $user = auth()->user();

        // Kicked-out check: staff who have left cannot use the panel
        if (method_exists($user, 'isLeft') && $user->isLeft()) {
            $leftAt = $user->left_at;
            auth()->logout();
            $request->session()->regenerate();

            return redirect()->route('login')->withErrors([
                'email' => 'You left Sigma on ' . $leftAt->format('d M Y') . '. You can no longer access the panel.',
            ]);
        }

        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
