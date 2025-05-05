<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class IncomingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'sender' => 'required|string|max:255',
            'letter_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'disposition' => ['required', Rule::in(['known', 'actioned', 'archived'])],
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        if (Auth::user()->role !== 'staff_bidang') {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'sender.required' => 'Pengirim wajib diisi.',
            'letter_number.required' => 'Nomor surat wajib diisi.',
            'letter_date.required' => 'Tanggal surat wajib diisi.',
            'subject.required' => 'Perihal wajib diisi.',
            'disposition.required' => 'Disposisi wajib dipilih.',
            'file_path.mimes' => 'File harus berupa PDF, JPG, JPEG, PNG.',
            'file_path.max' => 'Ukuran file maksimal 2MB.',
            'user_id.required' => 'User wajib dipilih.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
        ];
    }
}
