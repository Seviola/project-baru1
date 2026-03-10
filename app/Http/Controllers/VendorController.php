<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::latest()->paginate(10);
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Vendor::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $vendor->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui!');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus!');
    }

    // Method lama untuk restock (tetap dipertahankan)
    public function restock(Request $request)
    {
        $vendorId  = $request->vendor_id;
        $productId = $request->product_id;
        $qty       = $request->stock;

        $data = DB::table('vendor_products')
            ->where('vendor_id', $vendorId)
            ->where('product_id', $productId)
            ->first();

        if ($data) {
            DB::table('vendor_products')
                ->where('id', $data->id)
                ->update(['stock' => $data->stock + $qty]);
        } else {
            DB::table('vendor_products')->insert([
                'vendor_id'  => $vendorId,
                'product_id' => $productId,
                'stock'      => $qty,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Stock berhasil ditambah');
    }
}