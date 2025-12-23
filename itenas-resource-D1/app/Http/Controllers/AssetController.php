<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil kata kunci pencarian dari Search Bar
        $search = $request->input('search');

        // 2. Query ke Database dengan Relasi Lab & Prodi (Eager Loading)
        $assets = Asset::with(['lab.prodi'])
            // --- TAMBAHKAN FILTER STATUS DI SINI ---
            ->where('status', 'available') 
            // ---------------------------------------
            ->when($search, function ($query, $search) {
                // Logika Pencarian Global
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%")
                      ->orWhereHas('lab', function ($qLab) use ($search) {
                          $qLab->where('name', 'like', "%{$search}%")
                               ->orWhere('building_name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest() 
            ->paginate(12); 

        // 3. Tampilkan ke View
        return view('assets.index', compact('assets'));
    }
}