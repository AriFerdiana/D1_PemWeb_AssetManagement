<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Lab::with('prodi')->latest()->paginate(9);
        return view('rooms.index', compact('rooms'));
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