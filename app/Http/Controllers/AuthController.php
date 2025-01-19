<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Tolong isi email terlebih dahulu.',
            'email.email' => 'Tolong masukan email yang valid.',
            'password.required' => 'Tolong isi password terlebih dahulu.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return match ($user->role) {
                'user' => redirect('/user'),
                'admin' => redirect('/management'),
                default => redirect('/')->with('error', 'Role not recognized'),
            };
        }

        return redirect()->back()->withErrors(['error' => 'Invalid credentials'])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'no_SIM' => 'required|string|max:50',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Tolong isi email terlebih dahulu.',
            'email.email' => 'Tolong masukan email yang valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'name.required' => 'Tolong isi nama terlebih dahulu.',
            'name.max' => 'Nama terlalu panjang.',
            'alamat.required' => 'Tolong isi alamat terlebih dahulu.',
            'alamat.max' => 'Alamat terlalu panjang.',
            'no_telp.required' => 'Tolong isi nomor telepon terlebih dahulu.',
            'no_telp.max' => 'Nomor telepon terlalu panjang.',
            'no_SIM.required' => 'Tolong isi nomor SIM terlebih dahulu.',
            'no_SIM.max' => 'Nomor SIM terlalu panjang.',
            'password.required' => 'Tolong isi password terlebih dahulu.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'SIM' => $request->no_SIM,
            'role' => 'user',
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', 'Registration successful. You can now log in.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'You have been logged out.');
    }
}
