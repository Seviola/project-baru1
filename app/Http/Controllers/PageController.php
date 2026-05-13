<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function home()
    {
        $totalProducts = Product::count();
        $totalTransactionsToday = \App\Models\Transaction::whereDate('created_at', today())->count();

        $totalTransactionsMonth = \App\Models\Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $todayIncome = Transaction::whereDate('created_at', today())->sum('total');

        $monthIncome = \App\Models\Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lowStockProducts = Product::where('stock','<',5)
                            ->orderBy('stock','asc')
                            ->limit(5)
                            ->get();

        $topProducts = TransactionItem::select(
                'product_name',
                'class_type',
                DB::raw('SUM(qty) as total_sold')
            )
            ->groupBy('product_name', 'class_type')
            ->orderByDesc('total_sold')
            ->get();

        $weeklySales = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->whereDate('created_at','>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('home', compact(
            'totalProducts',
            'totalTransactionsToday',
            'totalTransactionsMonth',
            'todayIncome',
            'monthIncome',
            'lowStockProducts',
            'topProducts',
            'weeklySales'
        ));
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
