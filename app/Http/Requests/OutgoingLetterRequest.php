<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class OutgoingLetterRequest extends FormRequest
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
            'recipient' => 'required|string|max:100',
            'letter_number' => 'required|string|max:50',
            'letter_date' => 'required|date',
            'subject' => 'required|string|max:100',
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
            'recipient.required' => 'Penerima wajib diisi.',
            'recipient.max' => 'Penerima tidak lebih dari 100 karakter.',
            'letter_number.required' => 'Nomor surat wajib diisi.',
            'letter_number.string' => 'Nomor surat harus karakter.',
            'letter_number.max' => 'Nomor surat tidak lebih dari 50 karakter',
            'letter_date.required' => 'Tanggal surat wajib diisi.',
            'subject.required' => 'Perihal wajib diisi.',
            'subject.max' => 'Perihal tidak lebih dari 100 karakter.',
            'file_path.mimes' => 'File harus berupa PDF, JPG, JPEG, PNG.',
            'file_path.max' => 'Ukuran file maksimal 2MB.',
            'user_id.required' => 'User wajib dipilih.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
        ];
    }
}
