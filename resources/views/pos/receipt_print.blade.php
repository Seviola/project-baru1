<!DOCTYPE html>
<html>
<head>
    <title>KWITANSI</title>

    <style>
        body{
            font-family: monospace;
            width:300px;
            margin:auto;
            font-size:12px;
            color:#000;
        }

        h3,p{
            text-align:center;
            margin:2px 0;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        td{
            padding:2px 0;
            vertical-align: top;
        }

        .right{
            text-align:right;
        }

        .center{
            text-align:center;
        }

        hr{
            border:none;
            border-top:1px dashed #000;
            margin:6px 0;
        }

        .thanks{
            margin-top:10px;
            text-align:center;
            font-size:11px;
        }

        button{
            margin-top:10px;
            width:100%;
            padding:6px;
            cursor:pointer;
        }

        .social-table{
            width:100%;
            margin-top:5px;
            border-collapse: collapse;
        }

        .social-table td{
            width:50%;
            text-align:center;
            padding:2px 0;
            font-size:12px;
        }

        .social-table i{
            margin-right:4px;
        }

        @media print{
            button{
                display:none;
            }
        }
    </style>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<body onload="window.print()">

<script>
window.onafterprint = function(){
    window.location.href = "/kasir";
}
</script>

<h3>PT SCOMPTEC EDUKOM PERSADA</h3>
<p>
    <i class="fa-solid fa-location-dot"></i>
    Head Office: Jl. Kayon 24 Surabaya 60271 - Indonesia
</p>

<table class="social-table">
    <tr>
        <td>
            <i class="fa-solid fa-phone"></i> (031) 5315678
        </td>
        <td>
            <i class="fa-brands fa-instagram"></i> @Scomptec_learning
        </td>
    </tr>
    <tr>
        <td>
            <i class="fa-brands fa-facebook"></i> scomptec-learning
        </td>
        <td>
            <i class="fa-brands fa-tiktok"></i> scomptec.official
        </td>
    </tr>
</table>

<hr>

<p><b>NO :</b> {{ $transaction->invoice }}</p>
<p><b>Tanggal :</b> {{ now()->format('d-m-Y H:i:s') }}</p>

<hr>

<p style="text-align:left;">Kasir: {{ $transaction->user->name }}</p>
<p style="text-align:left;">Telah Terima Dari : {{ $transaction->student_name }}</p>
<p style="text-align:left;">
    Uang Sebanyak : Rp {{ number_format($transaction->total,0,',','.') }}
</p>

<p style="text-align:left;">
    Untuk Pembayaran :
    @foreach($transaction->items as $item)
        {{ $item->product_name }}<br>
        Jenis Kelas : {{ $item->class_type }}<br><br>
    @endforeach
</p>

<p style="text-align:left;">
    Jumlah : Rp {{ number_format($transaction->total,0,',','.') }}
</p>

<hr>

<table style="margin-top:20px;">
    <tr>
        <td style="width:50%; text-align:left;">
            Metode Pembayaran:<br>
            {{ $transaction->payment_method }}
        </td>

        <td style="width:50%; text-align:center;">
            Surabaya, {{ date('d-m-Y', strtotime($transaction->created_at)) }}<br><br><br><br>
            (_________________)
        </td>
    </tr>
</table>

<div class="thanks">
    Simpan kwitansi ini sebagai bukti pembayaran
</div>

<button onclick="window.print()">Print Ulang</button>

</body>
</html>