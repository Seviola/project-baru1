@extends('layouts.app')
@section('title', 'Edit Kelas')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    Edit Kelas
                </div>

                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}"
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
                            <label>Nama Kelas</label>
                            <input type="text" name="name" 
                                   value="{{ $product->name }}" 
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Pilih Kelas</label>
                            <select name="class_type" class="form-control">
                                <option value="">-- Pilih Kelas --</option>
                                <option value="Reguler" {{ $product->class_type == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                <option value="Private" {{ $product->class_type == 'Private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Biaya Kursus</label>
                            <input type="number" name="purchase_price" 
                                   value="{{ $product->purchase_price }}" 
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Ruang Kelas</label>
                            <input type="number" name="price" 
                                   value="{{ $product->price }}" 
                                   class="form-control"
                                   placeholder="Masukkan ruang kelas....">
                        </div>

                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                        </div>

                        <button class="btn btn-primary w-100">
                            Update Data Kelas
                        </button>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection