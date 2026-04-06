<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorProduct;

class RestockController extends Controller
{
    public function index()
    {
        $vendors  = Vendor::all();
        $products = Product::orderBy('name')->get();

        $pending = VendorProduct::with(['vendor', 'product'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('restock.index', compact('vendors', 'products', 'pending'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'  => 'required|exists:vendors,id',
            'product_id' => 'required|exists:products,id',
            'stock'      => 'required|integer|min:1',
        ]);

        VendorProduct::create([
            'vendor_id'      => $request->vendor_id,
            'product_id'     => $request->product_id,
            'stock'          => $request->stock,
            'status'         => 'pending',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('restock.index')
            ->with('success', 'Stok berhasil dikirim, menunggu persetujuan admin.');
    }

    public function approve(Request $request, VendorProduct $vendorProduct)
    {
        $request->validate([
            'approved_stock' => 'required|integer|min:1|max:' . $vendorProduct->stock,
        ]);

        $approvedQty = $request->approved_stock;

        $product = $vendorProduct->product;
        $product->stock += $approvedQty;
        $product->save();

        $vendorProduct->update([
            'approved_stock' => $approvedQty,
            'status'         => 'approved',
        ]);

        return redirect()->route('restock.index')
            ->with('success', "Stok {$approvedQty} unit berhasil dimasukkan ke kasir.");
    }

    public function reject(VendorProduct $vendorProduct)
    {
        $vendorProduct->update(['status' => 'rejected']);

        return redirect()->route('restock.index')
            ->with('success', 'Pengiriman stok ditolak.');
    }

    // Tandai pembayaran sudah lunas
    public function markPaid(VendorProduct $vendorProduct)
    {
        $vendorProduct->update([
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);

        return redirect()->route('restock.history')
            ->with('success', 'Pembayaran berhasil ditandai sebagai lunas.');
    }

    // Riwayat restock dengan filter payment status
    public function history(Request $request)
    {
        $query = VendorProduct::with(['vendor', 'product'])
            ->whereIn('status', ['approved', 'rejected']);

        // Filter berdasarkan payment_status
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $history = $query->latest()->paginate(15)->withQueryString();

        // Hitung ringkasan
        $totalPending = VendorProduct::where('status', 'approved')
            ->where('payment_status', 'pending')->count();
        $totalPaid = VendorProduct::where('status', 'approved')
            ->where('payment_status', 'paid')->count();

        return view('restock.history', compact('history', 'totalPending', 'totalPaid'));
    }
}