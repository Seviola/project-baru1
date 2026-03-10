@extends('layouts.app')
@section('title', 'Restock Produk')

@section('content')
<div class="container">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">

        {{-- KIRI: Form Vendor Kirim Stok --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="ti ti-truck"></i> Vendor Kirim Stok</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('restock.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Vendor</label>
                            <select name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Produk</label>
                            <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Stok Dikirim</label>
                            <input type="number" name="stock" min="1"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock') }}"
                                   placeholder="Contoh: 50" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-send"></i> Kirim Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KANAN: Daftar Stok Pending (Admin Sortir) --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ti ti-list-check"></i> Stok Masuk - Menunggu Persetujuan</h6>
                    <a href="{{ route('restock.history') }}" class="btn btn-outline-secondary btn-sm">
                        Riwayat
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Vendor</th>
                                <th>Produk</th>
                                <th>Stok Dikirim</th>
                                <th>Masukkan ke Kasir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pending as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->vendor->name ?? '-' }}</td>
                                <td>
                                    {{ $item->product->name ?? '-' }}
                                    <br>
                                    <small class="text-muted">Stok saat ini: {{ $item->product->stock ?? 0 }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark fs-6">
                                        {{ $item->stock }} unit
                                    </span>
                                </td>
                                <td width="160">
                                    {{-- Form approve dengan input jumlah --}}
                                    <form method="POST" action="{{ route('restock.approve', $item->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="approved_stock"
                                                   class="form-control"
                                                   value="{{ $item->stock }}"
                                                   min="1" max="{{ $item->stock }}" required>
                                            <button type="submit" class="btn btn-success"
                                                    title="Setujui & masukkan ke kasir"
                                                    onclick="return confirm('Masukkan stok ini ke kasir?')">
                                                <i class="ti ti-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('restock.reject', $item->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Tolak kiriman stok ini?')">
                                            <i class="ti ti-x"></i> Tolak
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada stok yang menunggu persetujuan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection