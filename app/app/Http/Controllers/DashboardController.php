<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $fy = FiscalYear::where('is_active', true)->first();

        $statsRaw = Item::selectRaw("
            COUNT(*) as total_items,
            COALESCE(SUM(pagu), 0) as total_pagu,
            COUNT(CASE WHEN verification_status = 'APPROVED' THEN 1 END) as approved,
            COUNT(CASE WHEN verification_status = 'PENDING' THEN 1 END) as pending,
            COUNT(CASE WHEN verification_status = 'REJECTED' THEN 1 END) as rejected,
            COALESCE(SUM(CASE WHEN verification_status = 'APPROVED' THEN pagu ELSE 0 END), 0) as pagu_approved
        ")->first();

        $stats = [
            'total_items'    => (int) ($statsRaw->total_items ?? 0),
            'total_pagu'     => (float) ($statsRaw->total_pagu ?? 0),
            'approved'       => (int) ($statsRaw->approved ?? 0),
            'pending'        => (int) ($statsRaw->pending ?? 0),
            'rejected'       => (int) ($statsRaw->rejected ?? 0),
            'pagu_approved'  => (float) ($statsRaw->pagu_approved ?? 0),
        ];

        // Recent items for BMA.006 — MVP focus
        $recentItems = Item::with([
                'account.subComponent.component.subOutput.output.program.fiscalYear'
            ])
            ->whereHas('account.subComponent.component.subOutput', function ($q) {
                $q->where('code', 'BMA.006');
            })
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $bma006 = \App\Models\SubOutput::where('code', 'BMA.006')->first();

        // ── SMART REMINDER BANNER UNTUK PERGANTIAN TAHUN ANGGARAN ──
        $newYearNotice = null;
        $currentCalYear = (int) date('Y');

        if ($fy) {
            if ($currentCalYear > $fy->year) {
                // Kalender sudah tahun baru (misal 2027), tapi DIPA aktif masih tahun lama (2026)!
                $targetFy = FiscalYear::where('year', $currentCalYear)->first();
                $targetHasStructure = $targetFy ? $targetFy->programs()->exists() : false;

                $newYearNotice = [
                    'type'                 => 'warning',
                    'title'                => "Tahun Kalender {$currentCalYear} Telah Dimulai!",
                    'message'              => "Saat ini kalender telah memasuki tahun {$currentCalYear}, namun DIPA aktif di sistem masih Tahun Anggaran {$fy->year}." . ($targetHasStructure ? " Struktur POK {$currentCalYear} sudah tersedia, silakan aktifkan tahun anggaran ini di menu Master Data." : " Belum ada struktur POK untuk {$currentCalYear}. Anda dapat menyalin seluruh kegiatan POK dari TA {$fy->year} dengan 1-klik agar pengarsipan tidak tertunda."),
                    'target_year'          => $currentCalYear,
                    'source_year'          => $fy->year,
                    'source_fy_id'         => $fy->id,
                    'target_has_structure' => $targetHasStructure,
                    'badge'                => "PERINGATAN TAHUN {$currentCalYear}",
                ];
            } else {
                // Memasuki periode H-2 / H-1 pergantian tahun (November s.d Desember): Rekomendasi persiapan DIPA tahun depan
                $nextYear = $fy->year + 1;
                $nextFy = FiscalYear::where('year', $nextYear)->first();
                $nextHasStructure = $nextFy ? $nextFy->programs()->exists() : false;

                // Muncul pada H-2 bulan (November) dan H-1 bulan (Desember) jika struktur tahun berikutnya belum disiapkan
                if ((int) date('n') >= 11 && !$nextHasStructure) {
                    $newYearNotice = [
                        'type'                 => 'info',
                        'title'                => "Persiapan DIPA Tahun Anggaran {$nextYear}",
                        'message'              => "Menjelang akhir tahun anggaran {$fy->year}, Anda dapat mempersiapkan struktur POK Tahun Anggaran {$nextYear} sekarang ({$stats['total_items']} item kegiatan). Struktur dapat disalin secara otomatis dengan dokumen SPJ yang bersih (siap pakai di awal tahun).",
                        'target_year'          => $nextYear,
                        'source_year'          => $fy->year,
                        'source_fy_id'         => $fy->id,
                        'target_has_structure' => false,
                        'badge'                => "PERSIAPAN DIPA {$nextYear}",
                    ];
                }
            }
        }

        return view('dashboard', compact('stats', 'recentItems', 'fy', 'bma006', 'newYearNotice'));
    }
}
