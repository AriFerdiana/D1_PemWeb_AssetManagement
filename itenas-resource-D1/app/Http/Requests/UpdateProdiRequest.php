<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID prodi yang sedang diedit
        $prodiId = $this->route('prodi')->id;

        return [
            'name' => 'required|string|max:255',
            // Unique tapi abaikan ID prodi ini
            'code' => 'required|string|max:10|unique:prodis,code,' . $prodiId,
            'faculty' => 'required|string',
            'location' => 'nullable|string',
        ];
    }
}