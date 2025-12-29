<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // Query Dasar
        $query = Lab::with('prodi');

        // 1. Fitur Pencarian (Nama Ruangan)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Fitur Filter (Nama Gedung)
        if ($request->filled('gedung')) {
            $query->where('building_name', $request->gedung);
        }

        // --- [PERBAIKAN: PAGINATION DINAMIS] ---
        // Default 9 agar sesuai grid 3 kolom, tapi bisa berubah sesuai request user
        $perPage = $request->input('per_page', 9);
        
        // Ambil data ruangan dengan pagination dinamis
        $rooms = $query->latest()->paginate($perPage)->withQueryString();
        // ---------------------------------------

        // Ambil daftar gedung unik untuk dropdown filter
        $buildings = Lab::select('building_name')
                        ->distinct()
                        ->orderBy('building_name')
                        ->pluck('building_name');

        // Ambil semua koordinat ruangan untuk ditampilkan di Peta Besar (Map Dashboard)
        $mapLocations = Lab::whereNotNull('latitude')
                           ->whereNotNull('longitude')
                           ->get(['id', 'name', 'building_name', 'latitude', 'longitude']);

        return view('rooms.index', compact('rooms', 'buildings', 'mapLocations'));
    }

    public function create(Lab $lab)
    {
        return view('rooms.create', compact('lab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'required|string',
            'proposal' => 'required|mimes:pdf|max:2048',
        ]);

        // --- VALIDASI MAKSIMAL 7 HARI ---
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        if ($start->diffInDays($end) > 7) {
            return back()->with('error', 'Maksimal durasi peminjaman ruangan adalah 7 hari.')->withInput();
        }

        // Cek Bentrok Jadwal
        $bentrok = Reservation::where('lab_id', $request->lab_id)
            ->whereIn('status', ['approved', 'borrowed', 'pending'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->exists();

        if ($bentrok) {
            return back()->with('error', 'Ruangan sudah dipesan pada jam tersebut!')->withInput();
        }

        $path = $request->file('proposal')->store('proposals', 'public');

        Reservation::create([
            'user_id' => Auth::id(),
            'lab_id' => $request->lab_id,
            'type' => 'room',
            'transaction_code' => 'ROOM-' . date('Ymd') . '-' . mt_rand(100, 999),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'status' => 'pending',
            'proposal_file' => $path,
        ]);

        return redirect()->route('reservations.rooms')->with('success', 'Booking ruangan berhasil diajukan!');
    }
}