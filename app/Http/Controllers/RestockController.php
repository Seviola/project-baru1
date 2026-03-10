<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class RestockController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $product = Product::where('name', $request->name)->first();

        if ($product) {

            // tambah stock jika produk sudah ada
            $product->stock += $request->stock;
            $product->purchase_price = $request->purchase_price;
            $product->price = $request->price;
            $product->save();

        } else {

            // buat produk baru
            Product::create([
                'name' => $request->name,
                'stock' => $request->stock,
                'purchase_price' => $request->purchase_price,
                'price' => $request->price
            ]);

        }

        return redirect()->back()->with('success','Restock berhasil');
    }
}