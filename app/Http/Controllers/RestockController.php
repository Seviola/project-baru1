<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorProduct;

class RestockController extends Controller
{
    // =============================================
    // HALAMAN 1: Vendor kirim stok ke vendor_products
    // =============================================
    public function index()
    {
        $vendors  = Vendor::all();
        $products = Product::orderBy('name')->get();

        // Daftar pengiriman stok dari vendor yang belum disetujui
        $pending = VendorProduct::with(['vendor', 'product'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('restock.index', compact('vendors', 'products', 'pending'));
    }

    // Vendor kirim stok (simpan ke vendor_products dengan status pending)
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'  => 'required|exists:vendors,id',
            'product_id' => 'required|exists:products,id',
            'stock'      => 'required|integer|min:1',
        ]);

        VendorProduct::create([
            'vendor_id'  => $request->vendor_id,
            'product_id' => $request->product_id,
            'stock'      => $request->stock,
            'status'     => 'pending',
        ]);

        return redirect()->route('restock.index')
            ->with('success', 'Stok berhasil dikirim, menunggu persetujuan admin.');
    }

    // =============================================
    // HALAMAN 2: Admin sortir stok masuk ke kasir
    // =============================================
    public function approve(Request $request, VendorProduct $vendorProduct)
    {
        $request->validate([
            'approved_stock' => 'required|integer|min:1|max:' . $vendorProduct->stock,
        ]);

        $approvedQty = $request->approved_stock;

        // Tambah stok ke produk
        $product = $vendorProduct->product;
        $product->stock += $approvedQty;
        $product->save();

        // Update status dan catat berapa yang disetujui
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

    // =============================================
    // HALAMAN 3: Riwayat semua restock
    // =============================================
    public function history()
    {
        $history = VendorProduct::with(['vendor', 'product'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->paginate(15);

        return view('restock.history', compact('history'));
    }
}