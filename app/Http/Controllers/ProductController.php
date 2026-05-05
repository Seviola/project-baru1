<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'barcode' => 'required|unique:products,barcode',
        'name' => 'required',
        'class_type' => 'required',
        'purchase_price' => 'required|numeric',
        'price' => 'required|numeric',
        'description' => 'nullable'
    ]);

    $data = [
        'barcode' => $request->barcode,
        'name' => $request->name,
        'class_type' => $request->class_type,
        'purchase_price' => $request->purchase_price,
        'price' => $request->price,
        'description' => $request->description
    ];

    Product::create($data);

    return redirect()->route('products.index')
        ->with('success', 'Data kelas berhasil ditambahkan');
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
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'name' => 'required',
            'class_type' => 'required',
            'purchase_price' => 'required|numeric',
            'price' => 'required',
            'description' => 'nullable',
        ]);

        $data = [
            'barcode' => $request->barcode,
            'name' => $request->name,
            'class_type' => $request->class_type,
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'description' => $request->description,
        ];

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Data kelas berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Data kelas berhasil dihapus');
    }
}