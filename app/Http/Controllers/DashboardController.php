<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;

        // Hitung jumlah surat masuk dan keluar
        $incomingCount = DB::table('incoming_letter')->count();
        $outgoingCount = DB::table('outgoing_letters')->count();

        $archiveCount = $incomingCount + $outgoingCount;

        // Rekap per tahun: surat masuk (SQLite pakai strftime('%Y', column))
        $incomingRekap = DB::table('incoming_letter')
            ->selectRaw("strftime('%Y', letter_date) as year, COUNT(*) as total")
            ->whereNotNull('letter_date')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Rekap per tahun: surat keluar
        $outgoingRekap = DB::table('outgoing_letters')
            ->selectRaw("strftime('%Y', letter_date) as year, COUNT(*) as total")
            ->whereNotNull('letter_date')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Gabungkan semua tahun
        $years = collect($incomingRekap)->pluck('year')
            ->merge($outgoingRekap->pluck('year'))
            ->unique()
            ->sort()
            ->values();

        $incomingData = $years->map(fn($year) => $incomingRekap->firstWhere('year', $year)->total ?? 0);
        $outgoingData = $years->map(fn($year) => $outgoingRekap->firstWhere('year', $year)->total ?? 0);

        // Rekap per bulan (SQLite pakai strftime('%m', column))
        $incomingPerMonthRaw = DB::table('incoming_letter')
            ->selectRaw("strftime('%m', letter_date) as month, COUNT(*) as total")
            ->whereRaw("strftime('%Y', letter_date) = ?", [$currentYear])
            ->groupByRaw("strftime('%m', letter_date)")
            ->pluck('total', 'month')
            ->all();

        $outgoingPerMonthRaw = DB::table('outgoing_letters')
            ->selectRaw("strftime('%m', letter_date) as month, COUNT(*) as total")
            ->whereRaw("strftime('%Y', letter_date) = ?", [$currentYear])
            ->groupByRaw("strftime('%m', letter_date)")
            ->pluck('total', 'month')
            ->all();

        // Konversi ke array 0–11
        $incomingPerMonth = array_fill(0, 12, 0);
        $outgoingPerMonth = array_fill(0, 12, 0);

        foreach ($incomingPerMonthRaw as $month => $total) {
            $incomingPerMonth[(int)$month - 1] = $total;
        }

        foreach ($outgoingPerMonthRaw as $month => $total) {
            $outgoingPerMonth[(int)$month - 1] = $total;
        }

        return view('dashboard', compact(
            'incomingCount',
            'outgoingCount',
            'archiveCount',
            'years',
            'incomingData',
            'outgoingData',
            'incomingPerMonth',
            'outgoingPerMonth'
        ));
    }
}
