<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomingLetterRequest;
use App\Models\IncomingLetter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\TestStatus\Incomplete;

class IncomingLetterController extends Controller
{
    public function index()
    {
        $search = request('search');

        if (Auth::user()->role == 'staff_bidang') {
            $incomingletter = IncomingLetter::query()
                ->when($search, function ($query, $search) {
                    $query->where('sender', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('letter_number', 'like', "%{$search}%");
                })
                ->where('user_id', Auth::user()->id)->latest()->paginate(10);
        } else {
            $incomingletter = IncomingLetter::query()
                ->when($search, function ($query, $search) {
                    $query->where('sender', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('letter_number', 'like', "%{$search}%");
                })
                ->latest()->paginate(10);
        }

        return view('incomingletter.index', compact('incomingletter'));
    }

    public function create()
    {
        $users = [];

        if (Auth::user()->role != 'staff_bidang') {
            $users = User::where('role', 'staff_bidang')->get();
        }

        return view('incomingletter.create', compact('users'));
    }

    public function store(IncomingLetterRequest $request)
    {
        $data = $request->validated();

        if (Auth::user()->role  == 'staff_bidang') {
            $data['user_id'] = Auth::user()->id;
        }

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('surat_masuk');
        }

        IncomingLetter::create($data);

        flash()->success('Surat Masuk Berhasil Ditambahkan.', ['title' => 'Berhasil']);

        return redirect()->route('surat-masuk.index');
    }

    public function edit(IncomingLetter $incomingletter)
    {
        // Cek apakah user memiliki hak akses
        if (Auth::user()->role == 'staff_bidang' && $incomingletter->user_id != Auth::user()->id) {
            abort(403);
        }

        $users = [];
        if (Auth::user()->role != 'staff_bidang') {
            $users = User::where('role', 'staff_bidang')->get();
        }

        return view('incomingletter.edit', compact('incomingletter', 'users'));
    }

    public function update(IncomingLetterRequest $request, IncomingLetter $incomingletter)
    {
        // Cek apakah user memiliki hak akses
        if (Auth::user()->role == 'staff_bidang' && $incomingletter->user_id != Auth::user()->id) {
            abort(403);
        }

        $data = $request->validated();

        if (Auth::user()->role == 'staff_bidang') {
            $data['user_id'] = Auth::user()->id;
        }

        // Jika ada file yang diupload, hapus file lama dan simpan yang baru
        if ($request->hasFile('file_path')) {
            if ($incomingletter->file_path) {
                Storage::delete($incomingletter->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('incomingletter');
        }

        $incomingletter->update($data);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil diperbarui.');
    }

    public function destroy(IncomingLetter $incomingletter)
    {
        // Cek apakah user memiliki hak akses
        if (Auth::user()->role == 'staff_bidang' && $incomingletter->user_id != Auth::user()->id) {
            abort(403);
        }

        // Hapus file jika ada
        if ($incomingletter->file_path) {
            Storage::delete($incomingletter->file_path);
        }

        $incomingletter->delete();

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil dihapus.');
    }

    public function viewFile($slug)
    {
        $letter = IncomingLetter::where('slug', $slug)->firstOrFail();

        // Periksa apakah file ada
        if ($letter->file_path && Storage::exists('public/' . $letter->file_path)) {
            // Mengembalikan file untuk dilihat
            return response()->file(storage_path('app/public/' . $letter->file_path));
        }

        return redirect()->route('surat-masuk.index')->with('error', 'File tidak ditemukan.');
    }

    public function downloadFile($slug)
    {
        $letter = IncomingLetter::where('slug', $slug)->firstOrFail();

        // Periksa apakah file ada
        if ($letter->file_path && Storage::exists('public/' . $letter->file_path)) {
            // Mengunduh file
            return Storage::download('public/' . $letter->file_path);
        }

        return redirect()->route('surat-masuk.index')->with('error', 'File tidak ditemukan.');
    }

    public function show($slug)
    {
        // Tandai sebagai sudah dibaca jika belum
        if (!$slug->read) {
            $slug->read = true;
            $slug->save();
        }

        return back();
    }
}
