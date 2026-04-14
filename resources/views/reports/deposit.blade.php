@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Report Setoran Kasir</h3>
        <button onclick="history.back()" class="btn btn-outline-secondary">
            &larr; Kembali
        </button>
    </div>

    <!-- FILTER -->
    <form method="GET" class="row mb-3">

        <div class="col-md-3">
            <input type="date" name="start_date" class="form-control">
        </div>

        <div class="col-md-3">
            <input type="date" name="end_date" class="form-control">
        </div>

        @if(auth()->user()->role == 'admin')
        <div class="col-md-3">
            <select name="user_id" class="form-control">
                <option value="">Semua Kasir</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="col-md-3">
            <button class="btn btn-warning">Filter</button>
        </div>

    </form>

    <!-- TABEL -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal Setoran</th>
                <th>Kasir</th>
                <th>Nominal Setoran</th>
            </tr>
        </thead>

        <tbody>
            @forelse($transactions as $trx)
            <tr>
                <td>{{ $trx->created_at->format('Y-m-d') }}</td>
                <td>{{ $trx->user->name }}</td>
                <td>Rp {{ number_format($trx->total) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>

    </table>

</div>
@endsection