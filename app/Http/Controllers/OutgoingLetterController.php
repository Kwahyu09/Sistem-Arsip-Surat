<?php

namespace App\Http\Controllers;

use App\Http\Requests\OutgoingLetterRequest;
use App\Models\OutgoingLetter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OutgoingLetterController extends Controller
{
    public function index()
    {
        $search = request('search');

        if (Auth::user()->role == 'staff_bidang') {
            $outgoingletter = OutgoingLetter::query()
                ->when($search, function ($query, $search) {
                    $query->where('recipient', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('letter_number', 'like', "%{$search}%");
                })
                ->where('user_id', Auth::user()->id)->latest()->paginate(10);
        } else {
            $outgoingletter = OutgoingLetter::query()
                ->when($search, function ($query, $search) {
                    $query->where('recipient', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('letter_number', 'like', "%{$search}%");
                })
                ->latest()->paginate(10);
        }

        return view('outgoingletter.index', compact('outgoingletter'));
    }

    public function create()
    {
        $users = [];

        if (Auth::user()->role != 'staff_bidang') {
            $users = User::where('role', 'staff_bidang')->get();
        }

        return view('outgoingletter.create', compact('users'));
    }

    public function store(OutgoingLetterRequest $request)
    {
        $data = $request->validated();

        if (Auth::user()->role  == 'staff_bidang') {
            $data['user_id'] = Auth::user()->id;
        }

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('surat_keluar');
        }

        OutgoingLetter::create($data);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil disimpan.');
    
    }

    public function edit(OutgoingLetter $outgoingletter)
    {
        // Cek apakah user memiliki hak akses
        if (Auth::user()->role == 'staff_bidang' && $outgoingletter->user_id != Auth::user()->id) {
            abort(403);
        }

        $users = [];
        if (Auth::user()->role != 'staff_bidang') {
            $users = User::where('role', 'staff_bidang')->get();
        }

        return view('outgoingletter.edit', compact('outgoingletter', 'users'));
    }

    public function update(OutgoingLetterRequest $request, OutgoingLetter $outgoingletter)
    {
        // Cek apakah user memiliki hak akses
        if (Auth::user()->role == 'staff_bidang' && $outgoingletter->user_id != Auth::user()->id) {
            abort(403);
        }

        $data = $request->validated();

        if (Auth::user()->role == 'staff_bidang') {
            $data['user_id'] = Auth::user()->id;
        }

        // Jika ada file yang diupload, hapus file lama dan simpan yang baru
        if ($request->hasFile('file_path')) {
            if ($outgoingletter->file_path) {
                Storage::delete($outgoingletter->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('surat_keluar');
        }

        $outgoingletter->update($data);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil diperbarui.');
    }

    public function destroy(OutgoingLetter $outgoingletter)
    {
        // Cek apakah user memiliki hak akses
        if (Auth::user()->role == 'staff_bidang' && $outgoingletter->user_id != Auth::user()->id) {
            abort(403);
        }

        // Hapus file jika ada
        if ($outgoingletter->file_path) {
            Storage::delete($outgoingletter->file_path);
        }

        $outgoingletter->delete();

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil dihapus.');
    }

    public function viewFile($slug)
    {
        $letter = OutgoingLetter::where('slug', $slug)->firstOrFail();

        // Periksa apakah file ada
        if ($letter->file_path && Storage::exists('public/' . $letter->file_path)) {
            // Mengembalikan file untuk dilihat
            return response()->file(storage_path('app/public/' . $letter->file_path));
        }

        return redirect()->route('surat-keluar.index')->with('error', 'File tidak ditemukan.');
    }

    public function downloadFile($slug)
    {
        $letter = OutgoingLetter::where('slug', $slug)->firstOrFail();

        // Periksa apakah file ada
        if ($letter->file_path && Storage::exists('public/' . $letter->file_path)) {
            // Mengunduh file
            return Storage::download('public/' . $letter->file_path);
        }

        return redirect()->route('surat-keluar.index')->with('error', 'File tidak ditemukan.');
    }
    
}
