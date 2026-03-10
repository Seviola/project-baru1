<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('vendor')->get();
        return view('products.index', compact('products'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = \App\Models\Vendor::all();
        return view('products.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $data = $request->all();

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    Product::create([
        'barcode' => $request->barcode,
        'name' => $request->name,
        'vendor_id' => $request->vendor_id, // PENTING
        'purchase_price' => $request->purchase_price,
        'price' => $request->price,
        'stock' => $request->stock,
        'description' => $request->description,
        'image' => $data['image'] ?? null
    ]);

    return redirect()->route('products.index')
        ->with('success','Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $vendors = Vendor::all();

        return view('products.edit', compact('product', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'name' => 'required',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {

            // hapus gambar lama jika ada
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }
}