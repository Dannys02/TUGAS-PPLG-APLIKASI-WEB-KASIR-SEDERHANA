<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::all();
        return view('categories.index', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        \App\Models\Category::create($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Category $category)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        $category->update($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil diubah.');
    }

    public function destroy(\App\Models\Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
