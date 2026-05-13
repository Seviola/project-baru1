<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function dailyReport()
    {
        $today = \Carbon\Carbon::today();
        $transactions = \App\Models\Transaction::with('items', 'user')
            ->whereDate('created_at', $today)
            ->get();
        return view('admin.report', compact('transactions'));
    }

    public function downloadPdf()
    {
        $transactions = \App\Models\Transaction::with('items', 'user')
            ->whereDate('created_at', now())
            ->get();

        $pdf = Pdf::loadView('reports.pdf', compact('transactions'));
        return $pdf->download('report_harian.pdf');
    }

    public function depositReport(Request $request)
    {
        $query =Transaction::with('user')
            ->where('is_deposited', 1);
        
        // Filter Tanggal
        if($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Kasir lihat milik sendiri
        if (auth()->user()->role === 'kasir') {
            $query->where('user_id', auth()->id());
        } else {

        // Admin bisa filter kasir
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
            }
        }
        
        $transactions = $query->latest()->get();
        $users = \App\Models\User::where('role', 'kasir')->get();

        return view('reports.deposit', compact('transactions', 'users'));
    }
}

