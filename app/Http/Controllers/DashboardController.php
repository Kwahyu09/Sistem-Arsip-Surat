<?php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\Rekap;
use App\Models\OutgoingLetter;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSuratMasuk = IncomingLetter::count();
        $totalSuratKeluar = OutgoingLetter::count();
        $totalRekap = Rekap::count();

        $suratMasukBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $suratMasukBulanan[] = IncomingLetter::whereMonth('tanggal_surat', $i)
                ->whereYear('tanggal_surat', Carbon::now()->year)
                ->count();
        }

        return view('dashboard', compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalRekap',
            'suratMasukBulanan'
        ));
    }
}
