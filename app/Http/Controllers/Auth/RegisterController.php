<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            'role'       => 'required|in:admin,kasir,vendor,user',
        ]);

        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Jika role vendor, otomatis buat data vendor
        if ($request->role === 'vendor') {
            Vendor::create([
                'name'    => $request->first_name . ' ' . $request->last_name,
                'email'   => $request->email,
                'user_id' => $user->id,
            ]);
        }

        return redirect('/login')->with('success', 'Akun berhasil dibuat, silakan login!');
    }
}