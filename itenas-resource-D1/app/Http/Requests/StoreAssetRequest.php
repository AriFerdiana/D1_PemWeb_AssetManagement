<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAssetRequest extends FormRequest
{
    /**
     * Siapa yang boleh melakukan request ini?
     */
    public function authorize(): bool
    {
        // Hanya Superadmin atau Laboran yang boleh akses
        return Auth::check() && Auth::user()->hasRole(['Superadmin', 'Laboran']);
    }

    /**
     * Aturan Validasi
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'serial_number' => 'required|unique:assets,serial_number',
            'lab_id' => 'required|exists:labs,id',
            'stock' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ];
    }

    /**
     * Pesan Error Kustom (Opsional, biar bahasa Indonesia)
     */
    public function messages(): array
    {
        return [
            'serial_number.unique' => 'Serial Number ini sudah terdaftar!',
            'lab_id.exists' => 'Lab yang dipilih tidak valid.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}