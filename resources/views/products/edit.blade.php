@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    Edit Produk
                </div>

                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST"
                        method="POST" 
                        enctype="multipart/form-data">    
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode" 
                                   value="{{ $product->barcode }}" 
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Nama Produk</label>
                            <input type="text" name="name" 
                                   value="{{ $product->name }}" 
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Harga Beli</label>
                            <input type="number" name="purchase_price" 
                                   value="{{ $product->purchase_price }}" 
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Harga Jual</label>
                            <input type="number" name="price" 
                                   value="{{ $product->price }}" 
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" 
                                   value="{{ $product->stock }}" 
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Gambar</label>

                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$product->image) }}" 
                                        width="120" 
                                        class="rounded shadow">
                                </div>
                            @endif

                            <input type="file" name="image" class="form-control">
                        </div>

                        <button class="btn btn-primary w-100">
                            Update Produk
                        </button>

                        @if($product->image)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/'.$product->image) }}" 
                                    width="120" 
                                    class="rounded shadow">
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection