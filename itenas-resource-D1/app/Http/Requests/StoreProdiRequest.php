<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ubah jadi true agar bisa dipakai
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:prodis,code',
            'faculty' => 'required|string',
            'location' => 'nullable|string',
        ];
    }

    // Opsional: Custom pesan error
    public function messages(): array
    {
        return [
            'code.unique' => 'Kode Prodi sudah terdaftar, gunakan kode lain.',
        ];
    }
}