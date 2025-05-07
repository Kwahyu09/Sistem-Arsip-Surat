<?php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'staff_bidang') {
            abort(403, 'Unauthorized');
        }
        // Ambil surat masuk
        $incoming = IncomingLetter::selectRaw('YEAR(letter_date) as year, COUNT(*) as total')
            ->whereNotNull('letter_date') // Pastikan tidak null
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // Ambil surat keluar
        $outgoing = OutgoingLetter::selectRaw('YEAR(letter_date) as year, COUNT(*) as total')
            ->whereNotNull('letter_date') // Hindari tanggal null
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // Gabungkan tahun unik dari keduanya
        $years = collect($incoming)->pluck('year')
            ->merge(collect($outgoing)->pluck('year'))
            ->unique()
            ->sort()
            ->values();

        // Cocokkan data berdasarkan tahun
        $incomingData = $years->map(fn($year) => $incoming->firstWhere('year', $year)->total ?? 0);
        $outgoingData = $years->map(fn($year) => $outgoing->firstWhere('year', $year)->total ?? 0);

        return view('rekap.index', [
            'years' => $years,
            'incomingData' => $incomingData,
            'outgoingData' => $outgoingData,
        ]);
    }
}
