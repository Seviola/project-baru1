<!DOCTYPE html>
<html>
<head>
<!-- Kwitansi pdf -->
    <meta charset="utf-8">
    <title>Kwitansi PDF</title>
    <style>
        @page {
            margin: 1cm 1cm 1cm 1cm;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
            color:#000;
            margin:10px;
            line-height:1.2;
        }

        .header{
            text-align:center;
            border-bottom:1px solid #000;
            padding-bottom:4px;
            margin-bottom:8px;
        }

        .logo{
            width:100%;
            max-width:500px;
            height:auto;
            margin-bottom:3px;
        }

        .header p{
            margin:1px 0;
            font-size:10px;
            color:#000;
        }

        .icon{
            width:9px;
            height:9px;
            vertical-align:middle;
            margin-right:3px;
        }

        .social-table{
            margin:2px auto 0;
            width:90%;
            font-size:10px;
            color:#000;
        }

        .social-table td{
            padding:1px 5px;
            text-align:left;
            vertical-align:middle;
        }

        .title{
            text-align:center;
            font-size:15px;
            font-weight:bold;
            margin-bottom:6px;
            text-decoration:underline;
            color:#000;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td{
            padding:1px 0;
            vertical-align:top;
            color:#000;
        }

        .line{
            border-top:1px dashed #000;
            margin:6px 0;
        }

        .item-box{
            margin-left:8px;
            margin-bottom:2px;
            color:#000;
        }

        p{
            margin:4px 0;
        }

        .ttd{
            margin-top:12px;
            text-align:right;
            color:#000;
        }

        .footer{
            margin-top:8px;
            text-align:center;
            font-size:9px;
            color:#000;
        }
    </style>
</head>
<body>

<div style="width:100%; max-width:700px; margin:0 auto; text-align:center;">

    <div class="header" style="text-align:center;">
        <img class="logo" src="{{ public_path('assets/images/logo-scomptec.png') }}"
             style="display:block; margin:0 auto; max-width:500px; width:100%;">

        <p style="margin:3px 0;">
            Head Office: Jl. Kayon 24 Surabaya 60271 - Indonesia
        </p>

        <table class="social-table" style="margin:5px auto 0; width:auto;">
            <tr>
                <td style="padding:2px 12px;">☎ (031) 5315678</td>
                <td style="padding:2px 12px;">IG:@Scomptec_learning</td>
            </tr>
            <tr>
                <td style="padding:2px 12px;">fb:scomptec-learning</td>
                <td style="padding:2px 12px;">Tiktok:scomptec.official</td>
            </tr>
        </table>
    </div>
</div>

    <div class="title">KWITANSI PEMBAYARAN</div>

    <table>
        <tr>
            <td width="35%">No Invoice</td>
            <td>: {{ $transaction->invoice }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ date('d F Y', strtotime($transaction->created_at)) }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $transaction->user->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <p><strong>Telah Terima Dari :</strong> {{ $transaction->student_name }}</p>
    <p><strong>Untuk Pembayaran :</strong></p>

    @foreach($transaction->items as $item)
    <div class="item-box">
        • {{ $item->product_name }}<br>
        Jenis Kelas : {{ $item->class_type }}
    </div>
    @endforeach

    <div class="line"></div>

    <table>
        <tr>
            <td width="35%">Jumlah Bayar</td>
            <td>: Rp {{ number_format($transaction->total,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Metode Bayar</td>
            <td>: {{ $transaction->payment_method }}</td>
        </tr>
    </table>

    <div class="ttd">
        Surabaya, {{ date('d F Y', strtotime($transaction->created_at)) }}

        <div style="height:80px;"></div>

        <div style="border-top:1px solid #000; width:200px; margin-left:auto;"></div>
        <br>
        <strong>{{ $transaction->user->name ?? '-' }}</strong>
    </div>

    <!--
    <div class="footer">
        Simpan kwitansi ini sebagai bukti pembayaran resmi.
    </div>
    -->

</div>

</body>
</html>