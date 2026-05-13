<!-- home -->
<h3 style="text-align:center;">Report Harian Kelas</h3>

<table border="1" width="100%" cellpadding="5" cellspacing="0">
    <tr>
        <th>Nama Kelas</th>
        <th>Tanggal</th>
        <th>Biaya Kursus</th>
        <th>Ruang Kelas</th>
        <th>Total</th>
        <th>Nama Siswa</th>
    </tr>

    @foreach($transactions as $trx)
        @foreach($trx->items as $item)
        <tr>
            <td>{{ $item->product_name }}
                @if($item->class_type)
                    - {{ $item->class_type }}
                @endif</td>
            <td> {{ $trx->created_at->format('d-m-Y') }}</td>
            <td> Rp {{ number_format($item->price,0,',','.') }}</td>
            <td>{{ $item->product->price ?? '-' }}</td>
            <td> Rp {{ number_format($item->subtotal,0,',','.') }}</td>
            <td>{{ $trx->student_name }}</td>
        </tr>
        @endforeach
    @endforeach
</table>