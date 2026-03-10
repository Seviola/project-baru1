@extends('layouts.app')
@section('title', 'Master Vendor')

@section('content')
<div class="container">

    {{-- Alert sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Master Vendor</h5>
            <a href="{{ route('vendor.create') }}" class="btn btn-primary btn-sm">
                + Tambah Vendor
            </a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Vendor</th>
                        <th>No. Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vendor->name }}</td>
                        <td>{{ $vendor->phone ?? '-' }}</td>
                        <td>{{ $vendor->email ?? '-' }}</td>
                        <td>{{ $vendor->address ?? '-' }}</td>
                        <td>
                            <a href="{{ route('vendor.edit', $vendor->id) }}"
                               class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('vendor.destroy', $vendor->id) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus vendor ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada data vendor
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end">
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection