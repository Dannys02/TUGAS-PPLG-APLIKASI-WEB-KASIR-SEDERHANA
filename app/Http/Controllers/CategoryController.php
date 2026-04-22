<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $editCategory = null;
        return view('categories.index', compact('categories', 'editCategory'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(\Illuminate\Http\Request $request, Category $category)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diubah.');
    }

    public function edit($id)
    {
        $categories = Category::all();
        $editCategory = Category::findOrFail($id);
        return view('categories.index', compact('categories', 'editCategory'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
