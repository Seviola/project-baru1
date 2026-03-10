@extends('layouts.app')
@section('title', 'Riwayat Restock')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Restock</h5>
            <a href="{{ route('restock.index') }}" class="btn btn-secondary btn-sm">
                &larr; Kembali
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Vendor</th>
                        <th>Produk</th>
                        <th>Stok Dikirim</th>
                        <th>Stok Disetujui</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->vendor->name ?? '-' }}</td>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td>{{ $item->stock }} unit</td>
                        <td>{{ $item->approved_stock ?? '-' }} {{ $item->approved_stock ? 'unit' : '' }}</td>
                        <td>
                            @if($item->status === 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($item->status === 'rejected')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada riwayat restock</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $history->links() }}
        </div>
    </div>
</div>
@endsection