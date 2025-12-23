<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Asset;
use Illuminate\Http\Request;

class AdminMaintenanceController extends Controller
{
    public function index() {
        $maintenances = Maintenance::with('asset')->latest()->paginate(10);
        return view('admin.maintenances.index', compact('maintenances'));
    }

    public function create() {
        $assets = Asset::all();
        return view('admin.maintenances.create', compact('assets'));
    }

    public function store(Request $request) {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'date' => 'required|date',
            'type' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
        ]);
        Maintenance::create($request->all());
        return redirect()->route('admin.maintenances.index')->with('success', 'Log Perawatan dicatat!');
    }

    public function destroy($id) {
        Maintenance::findOrFail($id)->delete();
        return back()->with('success', 'Log dihapus.');
    }
}