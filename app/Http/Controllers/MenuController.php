<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->get();
        $categories = Category::all();
        return view('menus.index', compact('menus', 'categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string'
        ]);
        Menu::create($request->all());
        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(\Illuminate\Http\Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string'
        ]);
        $menu->update($request->all());
        return redirect()->route('menus.index')->with('success', 'Menu berhasil diubah.');
    }

    public function edit($id)
    {
        $menus = Menu::with('category')->get();
        $categories = Category::all();
        $editMenu = Menu::findOrFail($id);
        return view('menus.index', compact('menus', 'categories', 'editMenu'));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
