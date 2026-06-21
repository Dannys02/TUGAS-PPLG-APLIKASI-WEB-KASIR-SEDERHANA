<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::where('user_id', auth()->user()->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_menu', 'like', "%{$search}%");
        }

        $menus = $query->paginate(5);

        $categories = Category::where('user_id', auth()->user()->id)->get();

        return view('menus.index', compact('menus', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menus')->where(function ($query) use ($request) {
                    return $query->where('kategori_id', $request->kategori_id)
                        ->where('user_id', auth()->user()->id);
                }),
            ],
            'kategori_id' => [
                'required',
                'exists:categories,id',
                // Pastikan kategori milik user
                Rule::exists('categories', 'id')->where('user_id', auth()->user()->id)
            ],
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            // Kumpulan pesan validasi bahasa Indonesia untuk method store
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'nama_menu.string'   => 'Nama menu harus berupa teks.',
            'nama_menu.max'      => 'Nama menu tidak boleh lebih dari 255 karakter.',
            'nama_menu.unique'   => 'Nama menu ini sudah terdaftar di kategori tersebut.',

            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid atau bukan milik Anda.',

            'harga.required' => 'Harga wajib diisi.',
            'harga.integer'  => 'Harga harus berupa angka bulat.',
            'harga.min'      => 'Harga tidak boleh kurang dari 0.',

            'stok.required' => 'Stok wajib diisi.',
            'stok.integer'  => 'Stok harus berupa angka bulat.',
            'stok.min'      => 'Stok tidak boleh kurang dari 0.',

            'deskripsi.string' => 'Deskripsi harus berupa teks.'
        ]);

        Menu::create(array_merge($request->all(), ['user_id' => auth()->user()->id]));
        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu)
    {
        // Pastikan menu milik user yang login
        if ($menu->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorised');
        }

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => [
                'required',
                'exists:categories,id',
                // Pastikan kategori milik user
                Rule::exists('categories', 'id')->where('user_id', auth()->user()->id)
            ],
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            // Kumpulan pesan validasi bahasa Indonesia untuk method update
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'nama_menu.string'   => 'Nama menu harus berupa teks.',
            'nama_menu.max'      => 'Nama menu tidak boleh lebih dari 255 karakter.',

            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid atau bukan milik Anda.',

            'harga.required' => 'Harga wajib diisi.',
            'harga.integer'  => 'Harga harus berupa angka bulat.',
            'harga.min'      => 'Harga tidak boleh kurang dari 0.',

            'stok.required' => 'Stok wajib diisi.',
            'stok.integer'  => 'Stok harus berupa angka bulat.',
            'stok.min'      => 'Stok tidak boleh kurang dari 0.',

            'deskripsi.string' => 'Deskripsi harus berupa teks.'
        ]);

        $menu->update($request->all());
        return redirect()->route('menus.index')->with('success', 'Menu berhasil diubah.');
    }

    public function edit($id)
    {
        $menus = Menu::where('user_id', auth()->user()->id)->with('category')->paginate(5);
        $categories = Category::where('user_id', auth()->user()->id)->get();
        $editMenu = Menu::where('user_id', auth()->user()->id)->findOrFail($id);
        return view('menus.index', compact('menus', 'categories', 'editMenu'));
    }

    public function destroy(Menu $menu)
    {
        // Pastikan menu milik user yang login
        if ($menu->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorised');
        }

        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
