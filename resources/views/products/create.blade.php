@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    Tambah Kelas
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
                            <label>Nama Kelas</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Pilih Kelas</label>
                            <select name="class_type" class="from-control">
                                <option value="">-- Pilih Kelas --</option>
                                <option value="Reguler">Reguler</option>
                                <option value="Private">Private</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Biaya Kursus</label>
                            <input type="number" name="purchase_price" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Ruang Kelas</label>
                            <input type="number" name="price" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="description" 
                                    class="form-control" 
                                    rows="3"
                                    placeholder="Masukkan keterangan kelas...."></textarea>
                        </div>

                        <button class="btn btn-success w-100">
                            Simpan Kelas
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection