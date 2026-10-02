<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\SubOutput;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->input('year', date('Y'));
        $month = $request->input('month');
        $week  = $request->input('week');

        $fiscalYears = FiscalYear::orderBy('year', 'desc')->get();

        // Helper to apply date filters
        $applyFilters = function ($query) use ($year, $month, $week) {
            $query->whereHas('account.subComponent.component.subOutput.output.program.fiscalYear', function ($q) use ($year) {
                $q->where('year', $year);
            });

            if ($month) {
                $query->whereMonth('updated_at', $month);

                if ($week) {
                    $startDay = (($week - 1) * 7) + 1;
                    $endDay   = min($week * 7, 31);
                    $query->whereBetween(\DB::raw('CAST(strftime("%d", updated_at) AS INTEGER)'), [$startDay, $endDay]);
                }
            }

            return $query;
        };

        $itemsQuery    = $applyFilters(Item::query());
        $totalItems    = (clone $itemsQuery)->count();
        $totalPagu     = (clone $itemsQuery)->sum('pagu');
        $approvedItems = (clone $itemsQuery)->where('verification_status', 'APPROVED')->count();
        $approvedPagu  = (clone $itemsQuery)->where('verification_status', 'APPROVED')->sum('pagu');
        $pendingItems  = (clone $itemsQuery)->where('verification_status', 'PENDING')->count();
        $rejectedItems = (clone $itemsQuery)->where('verification_status', 'REJECTED')->count();

        $summary = [
            'total_items'    => $totalItems,
            'total_pagu'     => $totalPagu,
            'approved_items' => $approvedItems,
            'approved_pagu'  => $approvedPagu,
            'pending_items'  => $pendingItems,
            'rejected_items' => $rejectedItems,
        ];

        $subOutputs = SubOutput::whereHas('output.program.fiscalYear', function ($q) use ($year) {
            $q->where('year', $year);
        })->with(['components.subComponents.accounts.items' => function ($q) use ($month, $week) {
            if ($month) {
                $q->whereMonth('updated_at', $month);
                if ($week) {
                    $startDay = (($week - 1) * 7) + 1;
                    $endDay   = min($week * 7, 31);
                    $q->whereBetween(\DB::raw('CAST(strftime("%d", updated_at) AS INTEGER)'), [$startDay, $endDay]);
                }
            }
        }])->paginate(15);

        return view('reports.index', compact('subOutputs', 'summary', 'fiscalYears', 'year', 'month', 'week'));
    }

    /**
     * Export rekapitulasi data to CSV (Excel compatible).
     */
        public function exportCsv(Request $request)
    {
        $year  = $request->input('year', date('Y'));
        $month = $request->input('month');
        $week  = $request->input('week');

        $monthNames = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
            7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];

        $periodeText = "Tahun Anggaran {$year}";
        if ($month) {
            $periodeText .= " - Bulan " . ($monthNames[(int)$month] ?? $month);
            if ($week) {
                $periodeText .= " (Minggu ke-{$week})";
            }
        }

        $subOutputs = SubOutput::whereHas('output.program.fiscalYear', function ($q) use ($year) {
            $q->where('year', $year);
        })->with(['output.program', 'components.subComponents.accounts.items' => function ($q) use ($month, $week) {
            if ($month) {
                $q->whereMonth('updated_at', $month);
                if ($week) {
                    $startDay = (($week - 1) * 7) + 1;
                    $endDay   = min($week * 7, 31);
                    $q->whereBetween(\DB::raw('CAST(strftime("%d", updated_at) AS INTEGER)'), [$startDay, $endDay]);
                }
            }
        }])->get();

        $filename = "Rekap_Keuangan_BPS_Subang_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($subOutputs, $periodeText) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 untuk Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            // ⭐ MAGIC LINE: beri tahu Excel delimiter-nya titik-koma (;)
            fwrite($file, "sep=;\n");

            // Helper: tulis baris dengan delimiter ;
            $writeRow = function ($row) use ($file) {
                // Bungkus setiap sel dengan quote agar aman dari koma/titik-koma
                $escaped = array_map(function ($cell) {
                    $cell = (string) $cell;
                    return '"' . str_replace('"', '""', $cell) . '"';
                }, $row);
                fwrite($file, implode(';', $escaped) . "\r\n");
            };

            $writeRow(['SISTEM DATA DIGITAL ARSIP KEUANGAN BPS KABUPATEN SUBANG']);
            $writeRow(['REKAPITULASI DAYA SERAP ANGGARAN POK']);
            $writeRow(['Periode:', $periodeText]);
            $writeRow([]);

            // Header tabel
            $writeRow([
                'No',
                'Kode Sub-Output',
                'Nama Sub-Output',
                'Program / Output',
                'Total Item',
                'Total Pagu (Rp)',
                'Item Approved',
                'Pagu Disetujui (Rp)',
                'Item Pending',
                'Item Rejected',
                'Persentase Serapan (%)'
            ]);

            $no = 1;
            $grandTotalPagu = 0;
            $grandApprovedPagu = 0;
            $grandItems = 0;

            foreach ($subOutputs as $so) {
                $items = collect();
                foreach ($so->components as $c) {
                    foreach ($c->subComponents as $sc) {
                        foreach ($sc->accounts as $acc) {
                            foreach ($acc->items as $it) {
                                $items->push($it);
                            }
                        }
                    }
                }

                $totalItemsCount = $items->count();
                $totalPagu = $items->sum('pagu');
                $approvedItems = $items->where('verification_status', 'APPROVED');
                $approvedCount = $approvedItems->count();
                $approvedPagu = $approvedItems->sum('pagu');
                $pendingCount = $items->where('verification_status', 'PENDING')->count();
                $rejectedCount = $items->where('verification_status', 'REJECTED')->count();
                $percent = $totalPagu > 0 ? round(($approvedPagu / $totalPagu) * 100, 2) : 0;

                $grandTotalPagu += $totalPagu;
                $grandApprovedPagu += $approvedPagu;
                $grandItems += $totalItemsCount;

                $writeRow([
                    $no++,
                    $so->code,
                    $so->name,
                    ($so->output->program->code ?? '') . ' / ' . ($so->output->code ?? ''),
                    $totalItemsCount,
                    number_format($totalPagu, 0, ',', '.'),          // ⭐ tanpa desimal
                    $approvedCount,
                    number_format($approvedPagu, 0, ',', '.'),
                    $pendingCount,
                    $rejectedCount,
                    number_format($percent, 2, ',', '.') . '%'       // ⭐ pakai koma untuk desimal
                ]);
            }

            $writeRow([]);
            $totalPercent = $grandTotalPagu > 0 ? round(($grandApprovedPagu / $grandTotalPagu) * 100, 2) : 0;
            $writeRow([
                '',
                'TOTAL KESELURUHAN',
                '',
                '',
                $grandItems,
                number_format($grandTotalPagu, 0, ',', '.'),
                '',
                number_format($grandApprovedPagu, 0, ',', '.'),
                '',
                '',
                number_format($totalPercent, 2, ',', '.') . '%'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
