<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // PENTING: Ubah false jadi true agar request diizinkan
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|unique:assets,code|max:50', // Kode harus unik
            'category_id' => 'required|exists:categories,id', // Harus ada di tabel kategori
            'lab_id'      => 'required|exists:labs,id',       // Harus ada di tabel lab
            'stock'       => 'required|integer|min:1',
            'prodi'       => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ];
    }

    /**
     * Custom pesan error (Opsional biar bahasa Indonesia)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama aset wajib diisi!',
            'code.unique'   => 'Kode barang sudah ada, ganti yang lain.',
            'stock.min'     => 'Stok minimal 1 dong.',
            'image.image'   => 'File harus berupa gambar.',
        ];
    }
}