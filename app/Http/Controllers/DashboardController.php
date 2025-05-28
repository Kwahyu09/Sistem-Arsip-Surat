<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
   public function index()
{
    $currentYear = Carbon::now()->year;

    // Hitung jumlah surat masuk dan keluar
    $incomingCount = IncomingLetter::count();
    $outgoingCount = OutgoingLetter::count();

    // Arsip adalah gabungan dari surat masuk dan keluar
    $archiveCount = $incomingCount + $outgoingCount;

    // Rekap per tahun: surat masuk
    $incomingRekap = IncomingLetter::selectRaw("strftime('%Y', letter_date) as year, COUNT(*) as total")
        ->whereNotNull('letter_date')
        ->groupBy('year')
        ->orderBy('year')
        ->get();

    // Rekap per tahun: surat keluar
    $outgoingRekap = OutgoingLetter::selectRaw("strftime('%Y', letter_date) as year, COUNT(*) as total")
        ->whereNotNull('letter_date')
        ->groupBy('year')
        ->orderBy('year')
        ->get();

    // Gabungkan semua tahun yang muncul
    $years = collect($incomingRekap)->pluck('year')
        ->merge($outgoingRekap->pluck('year'))
        ->unique()
        ->sort()
        ->values();

    $incomingData = $years->map(fn($year) => $incomingRekap->firstWhere('year', $year)->total ?? 0);
    $outgoingData = $years->map(fn($year) => $outgoingRekap->firstWhere('year', $year)->total ?? 0);

    // Rekap per bulan (grafik bar)
    $incomingPerMonthRaw = IncomingLetter::selectRaw('strftime("%m", letter_date) as month, COUNT(*) as total')
        ->whereYear('letter_date', $currentYear)
        ->groupByRaw('strftime("%m", letter_date)')
        ->pluck('total', 'month')
        ->all();

    $outgoingPerMonthRaw = OutgoingLetter::selectRaw('strftime("%m", letter_date) as month, COUNT(*) as total')
        ->whereYear('letter_date', $currentYear)
        ->groupByRaw('strftime("%m", letter_date)')
        ->pluck('total', 'month')
        ->all();

    // Konversi ke array numerik dari bulan 1-12
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
