<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AuthController extends Controller
{
  public function showRegister() {
    return view('auth.register');
  }

  public function register(Request $request) {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
    ], [
      'name.required' => 'Nama wajib diisi.',
      'name.string' => 'Nama harus berupa teks.',
      'name.max' => 'Nama maksimal 255 karakter.',

      'email.required' => 'Email wajib diisi.',
      'email.string' => 'Email harus berupa teks.',
      'email.email' => 'Format email tidak valid.',
      'email.max' => 'Email maksimal 255 karakter.',
      'email.unique' => 'Email ini sudah terdaftar.',

      'password.required' => 'Password wajib diisi.',
      'password.string' => 'Password harus berupa teks.',
      'password.min' => 'Password minimal 8 karakter.',
      'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Berhasil membuat akun, silakan login');
  }

  public function showLogin() {
    return view('auth.login');
  }

  public function login(Request $request) {
    $request->validate([
      'email' => 'required|string|email|max:255',
      'password' => 'required|string|min:8',
    ], [
      'email.required' => 'Email wajib diisi.',
      'email.string' => 'Email harus berupa teks.',
      'email.email' => 'Format email tidak valid.',
      'email.max' => 'Email maksimal 255 karakter.',
      'email.unique' => 'Email ini sudah terdaftar.',

      'password.required' => 'Password wajib diisi.',
      'password.string' => 'Password harus berupa teks.',
      'password.min' => 'Password minimal 8 karakter.',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();
      return redirect()->route('pos.index')->with('success', 'Berhasil login');
    }

    return back()->withErrors(['email' => 'Email atau password salah']);
  }

  public function logout(Request $request) {
    Auth::logout();
    $request->session()->regenerateToken();
    return redirect()->route('login')->with('success', 'Berhasil logout');
  }

  public function showSettings() {
    $user = Auth::user();
    return view('auth.settings', ['user' => $user]);
  }

  public function updateSettings(Request $request) {
    $user = Auth::user();

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $user->id,
      'password' => 'nullable|string|min:8|confirmed',
      'current_password' => 'nullable|required_with:password',
      'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
      'name.required' => 'Nama wajib diisi.',
      'name.string' => 'Nama harus berupa teks.',
      'name.max' => 'Nama maksimal 255 karakter.',

      'email.required' => 'Email wajib diisi.',
      'email.email' => 'Format email tidak valid.',
      'email.unique' => 'Email ini sudah digunakan oleh akun lain.',

      'password.string' => 'Password harus berupa teks.',
      'password.min' => 'Password minimal 8 karakter.',
      'password.confirmed' => 'Konfirmasi password tidak cocok.',

      'current_password.required_with' => 'Password lama wajib diisi jika ingin mengganti password.',

      'logo.image' => 'File logo harus berupa gambar.',
      'logo.mimes' => 'Logo hanya boleh berformat jpeg, png, jpg, atau gif.',
      'logo.max' => 'Ukuran logo maksimal 2MB.',
    ]);

    $userData = [
      'name' => $request->name,
      'email' => $request->email,
    ];

    // Handle logo upload
    if ($request->hasFile('logo')) {
      if ($user->logo) {
        Storage::disk('public')->delete('logos/' . $user->logo);
      }
      $file = $request->file('logo');
      $filename = time() . '_' . $file->getClientOriginalName();
      $file->storeAs('logos', $filename, 'public');
      $userData['logo'] = $filename;
    }

    // Handle password update
    if ($request->filled('password')) {
      if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
          'current_password' => 'Password saat ini salah'
        ]);
      }

      $userData['password'] = Hash::make($request->password);
    }

    $user->update($userData);

    return redirect()->route('settings.edit')->with('success', 'Profil berhasil diperbarui');
  }
}