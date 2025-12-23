<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReservationRequest extends FormRequest
{
    // Ubah jadi TRUE agar user boleh akses
    public function authorize()
    {
        return Auth::check(); 
    }

    // Pindahkan aturan validasi dari Controller ke sini
    public function rules()
    {
        // Deteksi ini request Aset atau Ruangan berdasarkan input yang ada
        if ($this->has('asset_id')) {
            // Validasi untuk Peminjaman ASET
            return [
                'asset_id' => 'required|exists:assets,id',
                'start_time' => 'required|date|after:now',
                'end_time' => 'required|date|after:start_time',
                'purpose' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'proposal_file' => 'nullable|file|mimes:pdf|max:2048',
            ];
        } else {
            // Validasi untuk Booking RUANGAN (RoomController)
            return [
                'lab_id' => 'required|exists:labs,id',
                'start_time' => 'required|date|after:now',
                'end_time' => 'required|date|after:start_time',
                'purpose' => 'required|string',
                'proposal' => 'required|mimes:pdf|max:2048',
            ];
        }
    }

    // (Opsional) Custom pesan error bahasa Indonesia
    public function messages()
    {
        return [
            'start_time.after' => 'Waktu mulai tidak boleh tanggal lampau.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'proposal.mimes' => 'Format proposal harus PDF.',
            'quantity.min' => 'Jumlah pinjam minimal 1.',
        ];
    }
}