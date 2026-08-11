<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SecurityHeaders Middleware
 *
 * Menyuntikkan HTTP Security Headers pada setiap respons web untuk
 * melindungi terhadap: Clickjacking, MIME sniffing, XSS, Referrer leakage,
 * dan akses fitur browser berbahaya.
 *
 * OWASP: A05 Security Misconfiguration
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ─── Clickjacking Protection ──────────────────────────────────────
        // Mencegah halaman di-embed di dalam iframe oleh situs lain.
        $response->headers->set('X-Frame-Options', 'DENY');

        // ─── MIME Sniffing Protection ─────────────────────────────────────
        // Mencegah browser menebak tipe konten di luar yang dideklarasikan.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ─── Referrer Policy ──────────────────────────────────────────────
        // Hanya kirim origin, bukan full URL, sebagai referrer.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ─── Permissions Policy ───────────────────────────────────────────
        // Nonaktifkan fitur browser yang tidak diperlukan sistem arsip ini.
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');

        // ─── Content Security Policy ──────────────────────────────────────
        // CSP hanya diterapkan pada environment production/staging agar tidak
        // mengganggu Vite Dev Server (http://[::1]:5173 / localhost) saat pengembangan.
        if (!app()->environment('local')) {
            $cspDirectives = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
                "font-src 'self' https://fonts.gstatic.com data:",
                "img-src 'self' data: blob:",
                "object-src 'none'",
                "frame-src 'self'",
                "connect-src 'self'",
                "base-uri 'self'",
                "form-action 'self'",
            ];

            if ($request->isSecure()) {
                $cspDirectives[] = "upgrade-insecure-requests";
            }

            $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
        }

        // ─── XSS Protection (Legacy browsers) ────────────────────────────
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ─── Remove Server Fingerprint ────────────────────────────────────
        // Sembunyikan informasi server untuk mengurangi serangan targeted.
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
