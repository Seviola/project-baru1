@extends('layouts.app')
@section('title','Arsip Transaksi Kasir')
<!-- kasir ke history -->
@section('content')
<div class="container">

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center" 
            style="background-color: #00458e; color: white;">
            <h4 class="mb-0" style="color: white;">Arsip Transaksi / Riwayat Kwitansi</h4>
        
            <a href="{{ route('kasir.index') }}" class="btn btn-outline-light btn-sm">
                <- Kembali
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Total</th>
                        <th>Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @php $no = 1; @endphp

                    @forelse($transactions as $trx)
                    <tr class="trx-row">
                        <td>{{ $no++ }}</td>
                        <td>{{ $trx->invoice }}</td>
                        <td>{{ $trx->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $trx->student_name }}</td>

                        <td>
                            @foreach($trx->items as $item)
                                {{ $item->product_name }} - {{ $item->class_type }}<br>
                            @endforeach
                        </td>

                        <td>Rp {{ number_format($trx->total,0,',','.') }}</td>
                        <td>{{ $trx->user->name ?? '-' }}</td>

                        <td>
                            <a href="/kasir/receipt/{{ $trx->id }}?from=arsip" class="btn btn-sm btn-success">
                                Cetak
                            </a>
                            <a href="/kasir/receipt-pdf/{{ $trx->id }}" class="btn btn-sm btn-danger">
                                PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Belum ada arsip transaksi
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection


@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function(){

    const searchInput = document.getElementById('search-product');

    if(searchInput){
        searchInput.addEventListener('keyup', function(){

            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll('.trx-row');

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