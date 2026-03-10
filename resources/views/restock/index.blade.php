@extends('layouts.app')
@section('title','Restock Produk')

@section('content')

<div class="container">

<div class="card">
<div class="card-header">
<h5>Restock Produk dari Vendor</h5>
</div>

<div class="card-body">

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

<form action="/vendor/restock" method="POST">
    @csrf

    <select name="vendor_id">
        @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
        @endforeach
    </select>

    <select name="product_id">
        @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
    </select>

    <input type="number" name="stock" placeholder="Jumlah stock">

    <button type="submit">Restock</button>
</form>

</div>
</div>

</div>

@endsection