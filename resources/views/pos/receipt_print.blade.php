<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>

    <style>
        body {
            font-family: monospace;
            width: 300px;
            margin: auto;
            font-size: 12px;
        }

        h3, p {
            text-align: center;
            margin: 2px 0;
        }

        table {
            width: 100%;
        }

        td {
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        hr {
            border: none;
            border-top: 1px dashed black;
            margin: 5px 0;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

<script>
    window.onafterprint = function() {
        window.location.href = "/kasir";
    };
</script>

    <h3>TOKO Sevi & Kifli</h3>
    <p>Jl. Manyor No. 1</p>
    <hr>

    <p>Invoice : {{ $transaction->invoice }}</p>
    <p>{{ $transaction->created_at }}</p>
    <p>Kasir: {{ $transaction->user->name }}</p>

    <hr>

    <table>
        @foreach($transaction->items as $item)
        <tr>
            <td colspan="2">{{ $item->product_name }}</td>
        </tr>
        <tr>
            <td>{{ $item->qty }} x {{ number_format($item->price,0,',','.') }}</td>
            <td class="right">
                {{ number_format($item->subtotal,0,',','.') }}
            </td>
        </tr>
        @endforeach
    </table>

    <hr>

    <table>
        <tr>
            <td>Total</td>
            <td class="right">
                Rp {{ number_format($transaction->total,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right">
                Rp {{ number_format($transaction->pay,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td class="right">
                Rp {{ number_format($transaction->change,0,',','.') }}
            </td>
        </tr>
    </table>

    <hr>

    <p>Terima Kasih 🙏</p>
    <p>Selamat menikmati makanan yang sudah anda beli!</p>
    <p>Kasir yang imott</p>

    <button onclick="window.print()">Print Ulang</button>

</body>
</html>