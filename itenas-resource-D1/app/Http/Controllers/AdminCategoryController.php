<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat!');
    }

    public function destroy($id) {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}