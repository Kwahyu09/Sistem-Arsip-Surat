<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;

class DashboardController extends Controller
{
    public function index()
    {
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

        // Gabungkan semua tahun yang muncul di surat masuk dan keluar
        $years = collect($incomingRekap)->pluck('year')
            ->merge($outgoingRekap->pluck('year'))
            ->unique()
            ->sort()
            ->values();

        // Cocokkan total per tahun
        $incomingData = $years->map(fn($year) => $incomingRekap->firstWhere('year', $year)->total ?? 0);
        $outgoingData = $years->map(fn($year) => $outgoingRekap->firstWhere('year', $year)->total ?? 0);

        return view('dashboard', compact(
            'incomingCount',
            'outgoingCount',
            'archiveCount',
            'years',
            'incomingData',
            'outgoingData'
        ));
    }
}
