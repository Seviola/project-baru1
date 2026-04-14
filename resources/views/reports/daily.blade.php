<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian</title>
    <th>Kasir</th>
    <td>{{ $transactions->user->name }}</td>
    <style>
        body { font-family: sans-serif; font-size: 12px;}
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Harian Kasir</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Harga Produk</th>
                <th>qty</th>
                <th>Total</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <thbody>
            @php $no = 1; @endphp
            @foreach($transactions as $trx)
            @foreach($trx->items as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $trx->created_at }}</td>
                <td>{{ number_format($item->price) }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->subtotal) }}</td>
                <td>{{ $trx->user->name }}</td>
            </tr>
            @endforeach
            @endforeach
        </thbody>
    </table>
    
</body>
</html>