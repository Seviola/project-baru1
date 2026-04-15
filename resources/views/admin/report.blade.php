@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div id="print-area">
        <h3>Report Harian Penjualan</h3>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Tanggal</th>
                    <th>Harga Produk</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    @foreach($trx->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $trx->created_at->format('Y-m-d') }}</td>
                        <td>{{ number_format($item->price,0,',','.') }}</td>
                        <td>{{ number_format($item->qty,0,',','.') }}</td>
                        <td>{{ number_format($item->subtotal,0,',','.') }}</td>
                        <td>{{ optional($trx->user)->name ?? '-' }}</td>
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