<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(5);
        $editCategory = null;
        return view('categories.index', compact('categories', 'editCategory'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori']);
        Category::create($request->all());

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diubah.');
    }

    public function edit($id)
    {
        $categories = Category::paginate(5);
        $editCategory = Category::findOrFail($id);
        return view('categories.index', compact('categories', 'editCategory'));
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return back()->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Kategori masih digunakan oleh menu!');
        }

        // $category->delete();
        // return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
