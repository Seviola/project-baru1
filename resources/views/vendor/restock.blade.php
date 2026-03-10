@extends('layouts.app')

@section('title', 'Vendor Restock')

@section('content')

<div class="container mt-4">

<h2 class="mb-4">Vendor Restock Barang</h2>

<form method="POST" action="{{ route('restock.store') }}">
@csrf

<div class="mb-3">
<label class="form-label">Nama Produk</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Jumlah Stock</label>
<input type="number" name="stock" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Harga Beli</label>
<input type="number" name="purchase_price" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Harga Jual</label>
<input type="number" name="price" class="form-control" required>
</div>

<button type="submit" class="btn btn-primary">
Tambah Stock
</button>

</form>

</div>

@endsection