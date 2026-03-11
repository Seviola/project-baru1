<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }
    public function iconTabler()
    {
        return view('icon-tabler');
    }
    public function typography()
    {
        return view('bc_typography');
    }

    public function color()
    {
        return view('bc_color');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('login');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect('/home');
            } elseif ($user->role == 'kasir') {
                return redirect('/kasir');
            } elseif ($user->role == 'vendor') {
                return redirect('/restock');
            } elseif ($user->role == 'user') {
                return redirect('/kasir');
            }
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function register()
    {
        return view('register');
    }

    public function samplePage()
    {
        return view('sample-page');
    }
    
}
