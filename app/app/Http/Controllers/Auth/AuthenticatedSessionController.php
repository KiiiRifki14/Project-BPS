<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * OWASP A07: Identification and Authentication Failures — audit logging.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // ─── Audit Log: Successful Login ──────────────────────────────────
        AuditLogger::log('LOGIN', "Login berhasil oleh [{$request->user()->nip_username}].");

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     * OWASP A07: Identification and Authentication Failures — session invalidation + audit.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ─── Audit Log: Logout ────────────────────────────────────────────
        AuditLogger::log('LOGOUT', "Logout oleh [{$request->user()?->nip_username}].");

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

