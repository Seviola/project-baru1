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
        <div class="col-md-5">
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

                        <label class="form-label fw-semibold">Daftar Produk</label>
                        <div id="product-rows">
                            <div class="product-row border rounded p-2 mb-2 bg-light position-relative">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 btn-remove-row" style="display:none;">
                                    <i class="ti ti-x"></i>
                                </button>
                                <div class="mb-2">
                                    <label class="form-label form-label-sm mb-1">Produk</label>
                                    <select name="items[0][product_id]" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label form-label-sm mb-1">Jumlah Stok</label>
                                    <input type="number" name="items[0][stock]" class="form-control form-control-sm" min="1" placeholder="Contoh: 50" required>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-3" id="btn-add-row">
                            <i class="ti ti-plus"></i> Tambah Produk
                        </button>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-send"></i> Kirim Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KANAN: Pending dikelompokkan per batch --}}
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ti ti-list-check"></i> Stok Masuk - Menunggu Persetujuan</h6>
                    <a href="{{ route('restock.history') }}" class="btn btn-outline-secondary btn-sm">Riwayat</a>
                </div>
                <div class="card-body p-2">

                    @forelse($pending as $batchKey => $items)
                    @php
                        $firstItem = $items->first();
                        $isBatch   = $items->count() > 1;
                        $batchId   = $firstItem->batch_id;
                    @endphp

                    <div class="card mb-3 border {{ $isBatch ? 'border-primary' : '' }}">
                        {{-- Header batch --}}
                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <span class="fw-semibold">{{ $firstItem->vendor->name ?? '-' }}</span>
                                @if($isBatch)
                                    <span class="badge bg-primary ms-2">{{ $items->count() }} produk</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ $firstItem->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            {{-- Tombol approve/reject batch --}}
                            @if($isBatch && $batchId)
                            <div class="d-flex gap-1">
                                <form method="POST" action="{{ route('restock.approveBatch', $batchId) }}">
                                    @csrf
                                    @method('PATCH')
                                    @foreach($items as $item)
                                        <input type="hidden" name="approved_stock_{{ $item->id }}" value="{{ $item->stock }}">
                                    @endforeach
                                    <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Setujui semua produk dalam batch ini?')">
                                        <i class="ti ti-check"></i> Setujui Semua
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('restock.rejectBatch', $batchId) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Tolak semua produk dalam batch ini?')">
                                        <i class="ti ti-x"></i> Tolak Semua
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>

                        {{-- Daftar produk dalam batch --}}
                        <div class="card-body p-0">
                            <table class="table table-sm table-bordered mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Stok Dikirim</th>
                                        <th>Masukkan ke Kasir</th>
                                        @if(!$isBatch)<th>Aksi</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product->name ?? '-' }}
                                            <br>
                                            <small class="text-muted">Stok saat ini: {{ $item->product->stock ?? 0 }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $item->stock }} unit</span>
                                        </td>
                                        <td width="140">
                                            @if(!$isBatch)
                                            {{-- Single item: approve per item --}}
                                            <form method="POST" action="{{ route('restock.approve', $item->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="approved_stock"
                                                           class="form-control"
                                                           value="{{ $item->stock }}"
                                                           min="1" max="{{ $item->stock }}" required>
                                                    <button type="submit" class="btn btn-success"
                                                            onclick="return confirm('Masukkan stok ini ke kasir?')">
                                                        <i class="ti ti-check"></i>
                                                    </button>
                                                </div>
                                            </form>
                                            @else
                                            {{-- Batch: input jumlah per item, submit lewat tombol "Setujui Semua" --}}
                                            <form method="POST" action="{{ route('restock.approveBatch', $batchId) }}" id="batch-form-{{ $batchId }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="approved_stock_{{ $item->id }}"
                                                       class="form-control form-control-sm"
                                                       value="{{ $item->stock }}"
                                                       min="1" max="{{ $item->stock }}">
                                            </form>
                                            @endif
                                        </td>
                                        @if(!$isBatch)
                                        <td>
                                            <form method="POST" action="{{ route('restock.reject', $item->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Tolak kiriman stok ini?')">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @empty
                    <div class="text-center text-muted py-4">
                        Tidak ada stok yang menunggu persetujuan
                    </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    const products = @json($products);
    let rowIndex = 1;

    function buildProductOptions() {
        let options = '<option value="">-- Pilih Produk --</option>';
        products.forEach(p => {
            options += `<option value="${p.id}">${p.name} (Stok: ${p.stock})</option>`;
        });
        return options;
    }

    function buildRow(index) {
        return `
        <div class="product-row border rounded p-2 mb-2 bg-light position-relative">
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 btn-remove-row">
                <i class="ti ti-x"></i>
            </button>
            <div class="mb-2">
                <label class="form-label form-label-sm mb-1">Produk</label>
                <select name="items[${index}][product_id]" class="form-select form-select-sm" required>
                    ${buildProductOptions()}
                </select>
            </div>
            <div>
                <label class="form-label form-label-sm mb-1">Jumlah Stok</label>
                <input type="number" name="items[${index}][stock]" class="form-control form-control-sm" min="1" placeholder="Contoh: 50" required>
            </div>
        </div>`;
    }

    document.getElementById('btn-add-row').addEventListener('click', function () {
        document.getElementById('product-rows').insertAdjacentHTML('beforeend', buildRow(rowIndex++));
        updateRemoveButtons();
    });

    document.getElementById('product-rows').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-row')) {
            e.target.closest('.product-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.product-row');
        rows.forEach(row => {
            row.querySelector('.btn-remove-row').style.display = rows.length > 1 ? 'block' : 'none';
        });
    }

    updateRemoveButtons();
</script>
@endsection