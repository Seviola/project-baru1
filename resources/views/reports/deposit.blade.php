@extends('layouts.app')

<!-- home & kasir -->
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Report Setoran Kasir</h3>
        @if(auth()->user()->isAdmin())
            <a href="{{ url('/home') }}" class="btn btn-outline-dark mb-3">
                &larr; Kembali
            </a>
        @else
            <a href="{{ url('/kasir') }}" class="btn btn-outline-dark mb-3">
                &larr; Kembali
            </a>
        @endif
    </div>

    <!-- FILTER -->
    <form method="GET" class="row mb-3">

        <div class="col-md-3">
            <input  type="date" 
                    name="start_date" 
                    class="form-control"
                    value="{{ request('start_date') }}">
        </div>

        <div class="col-md-3">
            <input  type="date" 
                    name="end_date" 
                    class="form-control"
                    value="{{ request('end_date') }}">
        </div>

        @if(auth()->user()->role == 'admin')
        <div class="col-md-3">
            <select name="user_id" class="form-control">
                <option value="">Semua Kasir</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
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
            <tr class="deposit-row">
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

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function(){

    const searchInput = document.getElementById('search-product');

    if(searchInput){

        searchInput.addEventListener('keyup', function(){

            let keyword = this.value.toLowerCase();

            let rows = document.querySelectorAll('.deposit-row');

            rows.forEach(function(row){

                let text = row.innerText.toLowerCase();

                if(text.includes(keyword)){
                    row.style.display = '';
                }else{
                    row.style.display = 'none';
                }

            });

        });

    }

});
</script>
@endsection