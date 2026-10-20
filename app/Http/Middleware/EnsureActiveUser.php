<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureActiveUser
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && method_exists(auth()->user(), 'isLeft') && auth()->user()->isLeft()) {
            $leftAt = auth()->user()->left_at;
            auth()->logout();
            $request->session()->regenerate();

            return redirect()->route('login')->withErrors([
                'email' => 'You left Sigma on ' . $leftAt->format('d M Y') . '. You can no longer access the panel.',
            ]);
        }

        return $next($request);
    }
}