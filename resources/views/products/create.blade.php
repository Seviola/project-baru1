@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    Tambah Produk
                </div>
                <div class="card-body">

                    <form action="{{ route('products.store') }}" 
                        method="POST" 
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Nama Produk</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Vendor</label>
                            <select name="vendor_id" class="form-control">
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">
                                    {{ $vendor->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Harga Beli</label>
                            <input type="number" name="purchase_price" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Harga Jual</label>
                            <input type="number" name="price" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Keterangan Produk</label>
                            <textarea name="description" 
                                    class="form-control" 
                                    rows="3"
                                    placeholder="Masukkan deskripsi produk..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Gambar Produk</label>
                            <input type="file" 
                                name="image" 
                                class="form-control">
                        </div>

                        <button class="btn btn-success w-100">
                            Simpan
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection