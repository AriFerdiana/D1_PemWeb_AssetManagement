<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    // 1. Tampilkan Daftar Ruangan (Katalog)
    public function index()
    {
        // Ambil data Lab (Ruangan)
        $rooms = Lab::latest()->paginate(9);
        return view('rooms.index', compact('rooms'));
    }

    // 2. Tampilkan Form Booking
    public function create(Lab $lab)
    {
        return view('rooms.create', compact('lab'));
    }

    // 3. Proses Simpan Peminjaman Ruangan
    public function store(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'required|string',
            'proposal' => 'required|mimes:pdf|max:2048', // Wajib PDF maks 2MB
        ]);

        // Cek Bentrok Jadwal (Sederhana)
        // Cek apakah ada booking lain di ruangan yg sama & jam yg beririsan
        $bentrok = Reservation::where('lab_id', $request->lab_id)
            ->where('status', '!=', 'rejected') // Hiraukan yang ditolak
            ->where('status', '!=', 'returned') // Hiraukan yang sudah selesai
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->exists();

        if ($bentrok) {
            return back()->with('error', 'Ruangan sudah dipesan pada jam tersebut! Silakan pilih waktu lain.');
        }

        // Upload Proposal
        $path = $request->file('proposal')->store('proposals', 'public');

        // Simpan ke Database
        Reservation::create([
            'user_id' => Auth::id(),
            'lab_id' => $request->lab_id,
            'type' => 'room', // <--- PENTING: Menandakan ini booking ruangan
            'transaction_code' => 'ROOM-' . mt_rand(1000, 9999),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'status' => 'pending', // Menunggu persetujuan Laboran
            'proposal_file' => $path,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Pengajuan peminjaman ruangan berhasil dikirim!');
    }
}