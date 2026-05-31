<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
  public function index(Request $request) {
    $query = Category::where('user_id', auth()->user()->id);

    // search
    if ($request->filled('search')) {
      $search = $request->input('search');
      $query->where('nama_kategori', 'like', "%{$search}%");
    }

    $categories = $query->paginate(5);

    $editCategory = null;
    return view('categories.index', compact('categories', 'editCategory'));
  }

  public function store(Request $request) {
    $request->validate([
      'nama_kategori' => [
        'required',
        'string',
        'max:255',
        Rule::unique('categories')
        ->where('user_id', auth()->user()->id)
      ]
    ]);
    Category::create([
      'nama_kategori' => $request->nama_kategori,
      'user_id' => auth()->user()->id
    ]);

    return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
  }

  public function update(Request $request, Category $category) {
    // Pastikan kategori milik user yang login
    if ($category->user_id !== auth()->user()->id) {
      abort(403, 'Unauthorised');
    }

    $request->validate(['nama_kategori' => 'required|string|max:255']);
    $category->update($request->all());
    return redirect()->route('categories.index')->with('success', 'Kategori berhasil diubah.');
  }

  public function edit($id) {
    $categories = Category::where('user_id', auth()->user()->id)->paginate(5);
    $editCategory = Category::where('user_id', auth()->user()->id)->findOrFail($id);
    return view('categories.index', compact('categories', 'editCategory'));
  }

  public function destroy(Category $category) {
    // Pastikan kategori milik user yang login
    if ($category->user_id !== auth()->user()->id) {
      abort(403, 'Unauthorised');
    }

    try {
      $category->delete();
      return back()->with('success', 'Kategori berhasil dihapus');
    } catch (\Exception $e) {
      return back()->with('error', 'Kategori masih digunakan oleh menu!');
    }
  }
}