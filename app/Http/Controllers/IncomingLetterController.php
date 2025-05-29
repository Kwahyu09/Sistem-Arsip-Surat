<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomingLetterRequest;
use App\Models\IncomingLetter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncomingLetterController extends Controller
{
    public function index()
    {
        $search = request('search');

        $query = IncomingLetter::query();

        if (Auth::user()->role === 'staff_bidang') {
            $query->where('user_id', Auth::id());
        }

        $query->when($search, function ($q, $search) {
            $q->where('sender', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")
                ->orWhere('letter_number', 'like', "%{$search}%");
        });

        $incomingletter = $query->latest()->paginate(10);

        return view('incomingletter.index', compact('incomingletter'));
    }

    public function create()
    {
        $users = [];

        if (Auth::user()->role !== 'staff_bidang') {
            $users = User::where('role', 'staff_bidang')->get();
        }

        return view('incomingletter.create', compact('users'));
    }

    public function store(IncomingLetterRequest $request)
    {
        $data = $request->validated();

        if (Auth::user()->role === 'staff_bidang') {
            $data['user_id'] = Auth::id();
        }

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('incomingletter');
        }

        IncomingLetter::create($data);

        return redirect()->route('incomingletter.index')->with('success', 'Surat Masuk berhasil ditambahkan.');
    }

    public function edit(IncomingLetter $incomingletter)
    {
        $this->authorizeAccess($incomingletter);

        $users = [];

        if (Auth::user()->role !== 'staff_bidang') {
            $users = User::where('role', 'staff_bidang')->get();
        }

        return view('incomingletter.edit', compact('incomingletter', 'users'));
    }

    public function update(IncomingLetterRequest $request, IncomingLetter $incomingletter)
    {
        $this->authorizeAccess($incomingletter);

        $data = $request->validated();

        if (Auth::user()->role === 'staff_bidang') {
            $data['user_id'] = Auth::id();
        }

        if ($request->hasFile('file_path')) {
            if ($incomingletter->file_path) {
                Storage::delete($incomingletter->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('incomingletter');
        }

        $incomingletter->update($data);

        return redirect()->route('incomingletter.index')->with('success', 'Surat Masuk berhasil diperbarui.');
    }

    public function destroy(IncomingLetter $incomingletter)
    {
        $this->authorizeAccess($incomingletter);

        if ($incomingletter->file_path) {
            Storage::delete($incomingletter->file_path);
        }

        $incomingletter->delete();

        return redirect()->route('incomingletter.index')->with('success', 'Surat Masuk berhasil dihapus.');
    }

    public function viewFile($slug)
    {
        $letter = IncomingLetter::where('slug', $slug)->firstOrFail();
        $this->authorizeAccess($letter);

        $filePath = storage_path('app/' . $letter->file_path);

        if ($letter->file_path && file_exists($filePath)) {
            return response()->file($filePath);
        }

        return redirect()->route('incomingletter.index')->with('error', 'File tidak ditemukan atau sudah dihapus.');
    }


    public function downloadFile($slug)
    {
        $letter = IncomingLetter::where('slug', $slug)->firstOrFail();

        $this->authorizeAccess($letter);

        if ($letter->file_path && Storage::exists($letter->file_path)) {
            return Storage::download($letter->file_path);
        }

        return redirect()->route('incomingletter.index')->with('error', 'File tidak ditemukan.');
    }

    public function markRead($slug)
    {
        $letter = IncomingLetter::where('slug', $slug)->firstOrFail();

        $this->authorizeAccess($letter);

        $letter->read = true;
        $letter->save();

        return back()->with('success', 'Surat ditandai sebagai telah dibaca.');
    }

    public function updateDisposition($slug)
    {
        $letter = IncomingLetter::where('slug', $slug)->firstOrFail();

        $this->authorizeAccess($letter);

        request()->validate([
            'disposition' => 'required|in:known,actioned,archived',
        ]);

        $letter->disposition = request('disposition');
        $letter->save();

        return back()->with('success', 'Disposisi surat berhasil diperbarui.');
    }

    /**
     * Membatasi akses hanya untuk admin, staf, atau staff_bidang yang memiliki surat tersebut.
     */
    private function authorizeAccess(IncomingLetter $letter)
    {
        if (Auth::user()->role === 'staff_bidang' && $letter->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }
    }
}
