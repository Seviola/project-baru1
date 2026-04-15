<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Support\Str;

class RestockController extends Controller
{
    public function index()
    {
        $vendors  = Vendor::all();
        $products = Product::orderBy('name')->get();

        // Ambil pending, kelompokkan per batch_id
        $pendingRaw = VendorProduct::with(['vendor', 'product'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Kelompokkan: yang punya batch_id digabung, yang tidak punya tetap sendiri
        $pending = $pendingRaw->groupBy(function ($item) {
            return $item->batch_id ?? 'single_' . $item->id;
        });

        return view('restock.index', compact('vendors', 'products', 'pending'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'          => 'required|exists:vendors,id',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.stock'      => 'required|integer|min:1',
        ]);

        $batchId = 'BATCH-' . strtoupper(Str::random(8));

        // Gabungkan stok jika produk sama
        $merged = [];
        foreach ($request->items as $item) {
            $pid = $item['product_id'];
            if (isset($merged[$pid])) {
                $merged[$pid] += $item['stock']; // tambah stok jika produk sama
            } else {
                $merged[$pid] = $item['stock'];
            }
        }

        foreach ($merged as $productId => $totalStock) {
            VendorProduct::create([
                'batch_id'       => $batchId,
                'vendor_id'      => $request->vendor_id,
                'product_id'     => $productId,
                'stock'          => $totalStock,
                'status'         => 'pending',
                'payment_status' => 'pending',
            ]);
        }

        $count = count($merged);
        return redirect()->route('restock.index')
            ->with('success', "Berhasil mengirim {$count} jenis produk, menunggu persetujuan admin.");
    }

    // Approve semua item dalam 1 batch sekaligus
    public function approveBatch(Request $request, $batchId)
    {
        $items = VendorProduct::where('batch_id', $batchId)
            ->where('status', 'pending')
            ->with('product')
            ->get();

        foreach ($items as $item) {
            $approvedQty = $request->input("approved_stock_{$item->id}", $item->stock);
            $approvedQty = min($approvedQty, $item->stock);

            $item->product->stock += $approvedQty;
            $item->product->save();

            $item->update([
                'approved_stock' => $approvedQty,
                'status'         => 'approved',
            ]);
        }

        return redirect()->route('restock.index')
            ->with('success', 'Semua produk dalam batch berhasil disetujui.');
    }

    // Reject semua item dalam 1 batch
    public function rejectBatch($batchId)
    {
        VendorProduct::where('batch_id', $batchId)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return redirect()->route('restock.index')
            ->with('success', 'Batch pengiriman stok ditolak.');
    }

    // Approve single item (untuk data lama tanpa batch_id)
    public function approve(Request $request, VendorProduct $vendorProduct)
    {
        $request->validate([
            'approved_stock' => 'required|integer|min:1|max:' . $vendorProduct->stock,
        ]);

        $approvedQty = $request->approved_stock;

        $vendorProduct->product->stock += $approvedQty;
        $vendorProduct->product->save();

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

    public function markPaid(VendorProduct $vendorProduct)
    {
        $vendorProduct->update([
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);

        return redirect()->route('restock.history')
            ->with('success', 'Pembayaran berhasil ditandai sebagai lunas.');
    }

    public function history(Request $request)
    {
        $query = VendorProduct::with(['vendor', 'product'])
            ->whereIn('status', ['approved', 'rejected']);

        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $historyRaw = $query->latest()->get();

        // Kelompokkan per batch_id
        $history = $historyRaw->groupBy(function ($item) {
            return $item->batch_id ?? 'single_' . $item->id;
        });

        $totalPending = VendorProduct::where('status', 'approved')
            ->where('payment_status', 'pending')->count();
        $totalPaid = VendorProduct::where('status', 'approved')
            ->where('payment_status', 'paid')->count();

        return view('restock.history', compact('history', 'totalPending', 'totalPaid'));
    }

    // Invoice single item
    public function invoice(VendorProduct $vendorProduct)
    {
        $restock = $vendorProduct->load(['vendor', 'product']);
        // Jika punya batch_id, redirect ke invoice batch
        if ($restock->batch_id) {
            return redirect()->route('restock.invoiceBatch', $restock->batch_id);
        }
        $items  = collect([$restock]);
        $vendor = $restock->vendor;
        $batchNo = 'RST-' . str_pad($restock->id, 5, '0', STR_PAD_LEFT);
        return view('restock.invoice', compact('items', 'vendor', 'batchNo'));
    }

    // Invoice batch (banyak produk)
    public function invoiceBatch($batchId)
    {
        $items = VendorProduct::with(['vendor', 'product'])
            ->where('batch_id', $batchId)
            ->get();

        if ($items->isEmpty()) {
            abort(404);
        }

        $vendor  = $items->first()->vendor;
        $batchNo = $batchId;
        return view('restock.invoice', compact('items', 'vendor', 'batchNo'));
    }

    public function markPaidBatch($batchId)
    {
        VendorProduct::where('batch_id', $batchId)
            ->where('status', 'approved')
            ->where('payment_status', 'pending')
            ->update([
                'payment_status' => 'paid',
                'paid_at'        => now(),
            ]);

        return redirect()->route('restock.history')
            ->with('success', 'Semua produk dalam batch berhasil ditandai lunas.');
    }

}