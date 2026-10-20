<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
  public function store(LoginRequest $request): RedirectResponse
{
    $credentials = $request->only('email', 'password');

    // 🔍 Find user BEFORE login
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    // Prevent staff who have left the company from logging in
    if ($user && method_exists($user, 'isLeft') && $user->isLeft()) {
        return back()->withErrors([
            'email' => 'You left Sigma on ' . $user->left_at->format('d M Y') . '. You can no longer access the panel.',
        ]);
    }

    // Prevent blocked users from logging in (check before attempting auth)
    if ($user && method_exists($user, 'isBlocked') && $user->isBlocked()) {
        return back()->withErrors([
            'email' => 'This account has been blocked. Contact admin.',
        ]);
    }

    // ❌ Invalid credentials
    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    /*
     * 🔒 Single-session guard.
     *
     * The old check compared `sessions.last_activity` against the full session
     * lifetime, so an idle-but-not-yet-swept row (very common after the browser
     * session itself expired) made the next legitimate login fail with
     * "already logged in on another device". Now we:
     *   - drop rows that are past the session lifetime,
     *   - only refuse when a session was seen very recently (genuinely active),
     *   - otherwise silently take over by clearing the stale rows.
     */
    if ($user) {
        $lifetime = (int) config('session.lifetime', 120);
        $activeWindow = now()->subSeconds((int) env('SESSION_ACTIVE_WINDOW', 600))->timestamp;
        $staleAfter   = now()->subMinutes($lifetime)->timestamp;

        try {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '<', $staleAfter)
                ->delete();

            $concurrentSession = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '>=', $activeWindow)
                ->when($request->hasSession(), fn ($q) => $q->where('id', '!=', $request->session()->getId()))
                ->exists();
        } catch (\Throwable $e) {
            // If we cannot read the sessions table, never block the login.
            \Illuminate\Support\Facades\Log::error('Session guard check failed: ' . $e->getMessage());
            $concurrentSession = false;
        }

        if ($concurrentSession) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'This account is currently active on another device. Log out there first, or try again in a few minutes.',
            ]);
        }

        // Take over: drop the leftover sessions of this user.
        try {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->when($request->hasSession(), fn ($q) => $q->where('id', '!=', $request->session()->getId()))
                ->delete();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Session takeover cleanup failed: ' . $e->getMessage());
        }
    }

    // 🔁 Regenerate session
    $request->session()->regenerate();

    // 🔥 THIS IS THE KEY LINE (bind user to session)
    DB::table('sessions')
        ->where('id', session()->getId())
        ->update(['user_id' => Auth::id()]);

    return match (Auth::user()->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'hr'       => redirect()->route('hr.dashboard'),
        'saleshead'=> redirect()->route('salehead.dashboard'),
        'salesman' => redirect()->route('salesman.dashboard'),
        default    => redirect('/dashboard'),
    };
}


    /**
     * Logout
     */
   public function destroy(Request $request): RedirectResponse
{
    if ($user = Auth::user()) {
        $user->session_id = null;
        $user->save();
    }

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

}
