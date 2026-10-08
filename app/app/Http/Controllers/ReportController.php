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

        // 1. Base query untuk seluruh item dalam Tahun Anggaran (Plafon POK tidak hilang)
        $fiscalYearItemsQuery = Item::whereHas('account.subComponent.component.subOutput.output.program.fiscalYear', function ($q) use ($year) {
            $q->where('year', $year);
        });

        $totalItems = (clone $fiscalYearItemsQuery)->count();
        $totalPagu  = (clone $fiscalYearItemsQuery)->sum('pagu');

        // 2. Filter rentang tanggal jika bulan dipilih
        $range = $this->getDateRange($year, $month, $week);

        if ($range) {
            $approvedQuery = (clone $fiscalYearItemsQuery)->where('verification_status', 'APPROVED')->whereBetween('updated_at', $range);
            $rejectedQuery = (clone $fiscalYearItemsQuery)->where('verification_status', 'REJECTED')->whereBetween('updated_at', $range);
        } else {
            $approvedQuery = (clone $fiscalYearItemsQuery)->where('verification_status', 'APPROVED');
            $rejectedQuery = (clone $fiscalYearItemsQuery)->where('verification_status', 'REJECTED');
        }

        $approvedItems = (clone $approvedQuery)->count();
        $approvedPagu  = (clone $approvedQuery)->sum('pagu');
        $rejectedItems = (clone $rejectedQuery)->count();
        $pendingItems  = max(0, $totalItems - $approvedItems - $rejectedItems);

        $summary = [
            'total_items'    => $totalItems,
            'total_pagu'     => $totalPagu,
            'approved_items' => $approvedItems,
            'approved_pagu'  => $approvedPagu,
            'pending_items'  => $pendingItems,
            'rejected_items' => $rejectedItems,
        ];

        // 3. Eager load seluruh hierarki sub-output untuk tahun anggaran ini
        $subOutputs = SubOutput::whereHas('output.program.fiscalYear', function ($q) use ($year) {
            $q->where('year', $year);
        })->with(['components.subComponents.accounts.items'])->paginate(15);

        return view('reports.index', compact('subOutputs', 'summary', 'fiscalYears', 'year', 'month', 'week', 'range'));
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

        $range = $this->getDateRange($year, $month, $week);

        $subOutputs = SubOutput::whereHas('output.program.fiscalYear', function ($q) use ($year) {
            $q->where('year', $year);
        })->with(['output.program', 'components.subComponents.accounts.items'])->get();

        $filename = "Rekap_Keuangan_BPS_Subang_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($subOutputs, $periodeText, $range) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 untuk Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            // ⭐ MAGIC LINE: beri tahu Excel delimiter-nya titik-koma (;)
            fwrite($file, "sep=;\n");

            // Helper: tulis baris dengan delimiter ;
            $writeRow = function ($row) use ($file) {
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

                if ($range) {
                    $startTime = strtotime($range[0]);
                    $endTime   = strtotime($range[1]);

                    $approvedItems = $items->filter(function ($it) use ($startTime, $endTime) {
                        if ($it->verification_status !== 'APPROVED') return false;
                        $t = strtotime($it->updated_at);
                        return $t >= $startTime && $t <= $endTime;
                    });

                    $rejectedItems = $items->filter(function ($it) use ($startTime, $endTime) {
                        if ($it->verification_status !== 'REJECTED') return false;
                        $t = strtotime($it->updated_at);
                        return $t >= $startTime && $t <= $endTime;
                    });
                } else {
                    $approvedItems = $items->where('verification_status', 'APPROVED');
                    $rejectedItems = $items->where('verification_status', 'REJECTED');
                }

                $approvedCount = $approvedItems->count();
                $approvedPagu  = $approvedItems->sum('pagu');
                $rejectedCount = $rejectedItems->count();
                $pendingCount  = max(0, $totalItemsCount - $approvedCount - $rejectedCount);
                $percent       = $totalPagu > 0 ? round(($approvedPagu / $totalPagu) * 100, 2) : 0;

                $grandTotalPagu += $totalPagu;
                $grandApprovedPagu += $approvedPagu;
                $grandItems += $totalItemsCount;

                $writeRow([
                    $no++,
                    $so->code,
                    $so->name,
                    ($so->output->program->code ?? '') . ' / ' . ($so->output->code ?? ''),
                    $totalItemsCount,
                    number_format($totalPagu, 0, ',', '.'),
                    $approvedCount,
                    number_format($approvedPagu, 0, ',', '.'),
                    $pendingCount,
                    $rejectedCount,
                    number_format($percent, 2, ',', '.') . '%'
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

    /**
     * Get start and end date for filtering [startDate, endDate], or null if no month selected.
     */
    private function getDateRange($year, $month, $week): ?array
    {
        $year = (int) $year;
        if (!$month) {
            return null;
        }

        $month = (int) $month;
        $startDay = 1;
        $endDay = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->day;

        if ($week) {
            $week = (int) $week;
            $startDay = (($week - 1) * 7) + 1;
            $endDay = min($week * 7, $endDay);
        }

        $startDate = sprintf('%04d-%02d-%02d 00:00:00', $year, $month, $startDay);
        $endDate   = sprintf('%04d-%02d-%02d 23:59:59', $year, $month, $endDay);

        return [$startDate, $endDate];
    }
}
