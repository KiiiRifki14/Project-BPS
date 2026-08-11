<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * AuditLogger — Security Activity Log Service
 *
 * Mencatat semua aktivitas kritis ke channel 'audit' yang terpisah dari
 * application log utama. File: storage/logs/audit.log
 *
 * OWASP A09: Security Logging and Monitoring Failures — mitigation.
 *
 * Format log:
 * [TIMESTAMP] [IP] [USER] [ACTION] MESSAGE
 */
class AuditLogger
{
    /**
     * Catat aktivitas keamanan/bisnis kritis ke audit log.
     *
     * @param string $action  Kode aksi: LOGIN, LOGOUT, DOCUMENT_UPLOAD, DOCUMENT_DELETE, ITEM_APPROVE, ITEM_REJECT, USER_CREATE, USER_DELETE, PASSWORD_RESET, ACCESS_DENIED
     * @param string $message Deskripsi aktivitas yang detail
     * @param array  $context Data tambahan opsional (akan di-encode JSON)
     */
    public static function log(string $action, string $message, array $context = []): void
    {
        $user    = Auth::user();
        $ip      = Request::ip();
        $userId  = $user ? "[{$user->nip_username} / {$user->role}]" : '[unauthenticated]';
        $ua      = Request::userAgent() ?? 'unknown';

        // Potong user-agent untuk keamanan (cegah log injection)
        $ua = mb_substr(preg_replace('/[\r\n\t]/', ' ', $ua), 0, 150);

        Log::channel('audit')->info("{$action} | {$userId} | IP:{$ip} | {$message}", array_merge([
            'action'     => $action,
            'user'       => $user?->nip_username,
            'role'       => $user?->role,
            'ip_address' => $ip,
            'user_agent' => $ua,
        ], $context));
    }

    /**
     * Catat akses yang ditolak (403) untuk mendeteksi probe/reconnaissance.
     */
    public static function denied(string $resource, string $reason = ''): void
    {
        static::log('ACCESS_DENIED', "Akses ditolak ke [{$resource}]. Alasan: {$reason}");
    }
}
