<h3 style="text-align:center;">Report Kasir Hari Ini</h3>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>Produk</th>
        <th>Tanggal</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Kasir</th>
    </tr>

    @foreach($transactions as $trx)
        @foreach($trx->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $trx->created_at->format('Y-m-d') }}</td>
            <td>{{ number_format($item->price) }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ number_format($item->subtotal) }}</td>
            <td>{{ optional($trx->user)->name ?? '-' }}</td>
        </tr>
        @endforeach
    @endforeach
</table>