<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function restock(Request $request)
    {
        $vendorId = $request->vendor_id;
        $productId = $request->product_id;
        $qty = $request->stock;

        $data = DB::table('vendor_products')
            ->where('vendor_id', $vendorId)
            ->where('product_id', $productId)
            ->first();

        if ($data) {
            DB::table('vendor_products')
                ->where('id', $data->id)
                ->update([
                    'stock' => $data->stock + $qty
                ]);
        } else {
            DB::table('vendor_products')->insert([
                'vendor_id' => $vendorId,
                'product_id' => $productId,
                'stock' => $qty,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Stock berhasil ditambah');
    }
}