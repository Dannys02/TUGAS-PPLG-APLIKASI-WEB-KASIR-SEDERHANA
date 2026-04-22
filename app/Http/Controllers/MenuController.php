<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = \App\Models\Menu::with('category')->get();
        $categories = \App\Models\Category::all();
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
        \App\Models\Menu::create($request->all());
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Menu $menu)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string'
        ]);
        $menu->update($request->all());
        return redirect()->back()->with('success', 'Menu berhasil diubah.');
    }

    public function destroy(\App\Models\Menu $menu)
    {
        $menu->delete();
        return redirect()->back()->with('success', 'Menu berhasil dihapus.');
    }
}
