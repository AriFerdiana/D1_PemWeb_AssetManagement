<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Jangan lupa ubah jadi true
    }

    public function rules(): array
    {
        // Ambil ID aset yang sedang diedit dari route
        // Misal route-nya: /assets/{asset}
        $assetId = $this->route('asset') ? $this->route('asset')->id : null;

        return [
            'name'        => 'required|string|max:255',
            // Ignore ID ini saat cek unique, biar gak dianggap duplikat sama diri sendiri
            'code'        => 'required|string|max:50|unique:assets,code,' . $assetId, 
            'category_id' => 'required|exists:categories,id',
            'lab_id'      => 'required|exists:labs,id',
            'stock'       => 'required|integer|min:0', // Pas update boleh 0 kalau habis
            'prodi'       => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}