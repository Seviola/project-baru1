@extends('layouts.app')
@section('title', 'Riwayat Restock')

@section('content')
<div class="container">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="avtar avtar-s bg-light-warning">
                        <i class="ti ti-clock f-24 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Belum Dibayar</h6>
                        <h4 class="mb-0 text-warning fw-bold">{{ $totalPending }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="avtar avtar-s bg-light-success">
                        <i class="ti ti-circle-check f-24 text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Sudah Dibayar</h6>
                        <h4 class="mb-0 text-success fw-bold">{{ $totalPaid }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Restock</h5>
            <a href="{{ route('restock.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali</a>
        </div>

        {{-- Filter --}}
        <div class="card-body border-bottom pb-3">
            <form method="GET" action="{{ route('restock.history') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Status Pembayaran</label>
                    <select name="payment" class="form-select form-select-sm">
                        <option value="">-- Semua --</option>
                        <option value="pending" {{ request('payment') == 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                        <option value="paid"    {{ request('payment') == 'paid'    ? 'selected' : '' }}>Sudah Dibayar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('restock.history') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-3">
            @forelse($history as $batchKey => $items)
            @php
                $firstItem = $items->first();
                $isBatch   = $items->count() > 1;
                $batchId   = $firstItem->batch_id;

                // Cek apakah semua item dalam batch sudah paid
                $allPaid     = $items->every(fn($i) => $i->payment_status === 'paid');
                $anyApproved = $items->contains(fn($i) => $i->status === 'approved');
                $anyPending  = $items->contains(fn($i) => $i->payment_status === 'pending' && $i->status === 'approved');
            @endphp

            <div class="card mb-3 border {{ $isBatch ? 'border-primary' : 'border-secondary' }}">
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
                    <div class="d-flex gap-2 align-items-center">
                        {{-- Status bayar keseluruhan batch --}}
                        @if($allPaid)
                            <span class="badge bg-success">Lunas</span>
                        @elseif($anyPending)
                            <span class="badge bg-warning text-dark">Belum Bayar</span>
                        @endif

                        {{-- Tombol Tandai Lunas (batch) --}}
                        @if($isBatch && $anyPending && $batchId)
                        <form method="POST" action="{{ route('restock.markPaidBatch', $batchId) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Tandai semua produk dalam batch ini sebagai lunas?')">
                                <i class="ti ti-check"></i> Tandai Lunas
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                {{-- Tabel produk dalam batch --}}
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Stok Dikirim</th>
                                <th>Stok Disetujui</th>
                                <th>Status Restock</th>
                                <th>Status Bayar</th>
                                <th>Tanggal Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td>{{ $item->stock }} unit</td>
                                <td>{{ $item->approved_stock ? $item->approved_stock . ' unit' : '-' }}</td>
                                <td>
                                    @if($item->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($item->status === 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->payment_status === 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>{{ $item->paid_at ? $item->paid_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @if($isBatch && $batchId)
                                    <a href="{{ route('restock.invoiceBatch', $batchId) }}" target="_blank"
                                       class="btn btn-info btn-sm mb-1">
                                        <i class="ti ti-file-invoice"></i> Invoice Batch
                                    </a>
                                    @else
                                    <a href="{{ route('restock.invoice', $item->id) }}" target="_blank"
                                       class="btn btn-info btn-sm mb-1">
                                        <i class="ti ti-file-invoice"></i> Invoice
                                    </a>
                                    @endif
                                    {{-- Tombol Tandai Lunas per item (hanya untuk single item) --}}
                                    @if(!$isBatch && $item->status === 'approved' && $item->payment_status === 'pending')
                                    <form method="POST" action="{{ route('restock.markPaid', $item->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Tandai sebagai lunas?')">
                                            <i class="ti ti-check"></i> Tandai Lunas
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @empty
            <div class="text-center text-muted py-4">Belum ada riwayat restock</div>
            @endforelse
        </div>
    </div>
</div>
@endsection