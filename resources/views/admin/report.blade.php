@extends('layouts.app')
<!-- home -->
@section('content')
<div class="container mt-4">

    <div id="print-area">
        <h3>Report Harian Kelas</h3>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tanggal</th>
                    <th>Biaya Kursus</th>
                    <th>Ruang Kelas</th>
                    <th>Total</th>
                    <th>Nama Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    @foreach($trx->items as $item)
                    <tr class="report-row">
                        <td>{{ $item->product_name }}
                            @if($item->class_type)
                                - {{ $item->class_type }}
                            @endif</td>
                        <td>{{ $trx->created_at->format('d-m-Y') }}</td>
                        <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                        <td>{{ $item->product->price ?? '-' }}</td>
                        <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                        <td>{{ $trx->student_name }}</td>
                    </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <button onclick="printReport()" class="btn btn-outline-info mb-3">
        Print Report
    </button>

    <a href="/report/pdf" class="btn btn-outline-warning mb-3">
        Versi pdf
    </a>

    <a href="{{ url('/home') }}" class="btn btn-outline-dark mb-3">
        &larr; Kembali
    </a>
    
</div>
@endsection

@section('scripts')
<script>
function printReport() {
    window.print();
}

document.addEventListener("DOMContentLoaded", function(){
    const searchInput = document.getElementById('search-product');
    if(searchInput){
        searchInput.addEventListener('keyup', function(){
            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll('.report-row');
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

<style>
@media print {

    body * {
        visibility: hidden;
    }

    #print-area, #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        font-family: monospace;
        font-size: 11px;
    }

    button {
        display: none;
    }

    /* Optional: biar seperti struk */
    #print-area {
        font-family: monospace;
        font-size: 11px;
    }
}
</style>
@endsection