<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi PDF</title>

    <style>
        @page {
            size: 20.5cm 10.8cm;
            margin: 0;
        }

        body{
            margin:0;
            padding:0;
            font-family:DejaVu Sans, sans-serif;
            font-size:12px;
            color:#2f2f7f;
        }

        .wrapper{
            width:20.5cm;
            height:10.8cm;
            overflow:hidden;
            position:relative;
        }

        /* SIDEBAR KIRI */
        .sidebar{
            position:absolute;
            top:0;
            left:0;
            width:3.5cm;
            height:10.8cm;
            background:#fff;
        }

        .sidebar-inner{
            position:absolute;
            top:o; 
            left:0;
            width:10.8cm;
            height:3.5cm;

            /* putar seperti contoh gambar */
            transform:rotate(-90deg) translateX(-100%);
            transform-origin:top left;

            box-sizing:border-box;

            /* dibuat center */
            display:flex;
            flex-direction:column;
            justify-content:center;
            padding:2px 4px;
        }

        .sidebar-content{
            display:flex;
            flex-direction: column; /* logo di atas, info di bawah */
            align-items:center;
            justify-content:center;
            height:100%;
            transform:translateY(0.5cm);
        }

        /* Logo diperbesar */
        .logo-area{
            width:8cm; /* kecilkan gambar */
            display:flex;
            justify-content:center;
            align-items:center;
            margin:0 auto; /* posisi tetap di tengah */
        }

        .logo{
            width:100%;
            height:auto;
            display:block;
        }

        .info-area{
            padding-left:0;
            margin-top:4px;
            font-size:10px;
            line-height:1.2;
            color:#2f2f7f;

            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            text-align:center; /* bikin semua text ke tengah */
        }

        .info-area p{
            margin:0;
        }

        /* supaya sejajar */
        .social-row{
            width:100%;
            margin-top:4px;
            border-collapse:collapse;
        }

        .social-row td{
            font-size:9px;
            color:#2f2f7f;
            padding:1px 4px;
            white-space:nowrap;
            text-align:left;
            font-weight:bold;
        }

        .social-row td{
            width:auto;
            font-size:10px;
            color:#2f2f7f;
            vertical-align:top;
            padding:0;   /* hilangkan jarak */
            margin:0;
            line-height:1.1;
            text-align:center;
            white-space:nowrap; /* biar tidak turun baris */
        }

        .icon{
            width:9px;
            height:9px;
            vertical-align:middle;
            margin-right:3px;
        }

        /* biru */
        .bottom-line{
            position:absolute;
            left:0;
            right:0;
            bottom:0;
            height:3px;
            background:#2f2f7f;
        }

        /* KONTEN KANAN*/
        .content{
            position:absolute;
            top:0;
            left:3.5cm; /* sejajar setelah sidebar */
            width:17cm; /* 20.5 - 3.5 */
            height:10.8cm;
            padding:12px 15px;
            box-sizing:border-box;
            
        }

        .title{
            text-align:right;
            font-size:24px;
            font-weight:bold;
            font-style:italic;
            color:#2f2f7f;
            margin-bottom:14px;
            padding-right:2cm;
            letter-spacing:1px;
            box-sizing:border-box;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td{
            padding:2px 0;
            vertical-align:top;
            font-size:13px;
            font-weight:bold;
            color:#2f2f7f;
        }

        .line{
            /* border-top:1px dashed #000;
            margin:8px 0; */
            display:none;
        }

        .item-box{
            margin-left:10px;
            margin-bottom:4px;
        }

        .ttd{
            margin-top:18px;
            text-align:right;
            padding-right:2cm;
            box-sizing:border-box;
        }

        .amount{
            font-size:15px;
            font-weight:bold;
            color:#1f1f8f;
        }

        .form-row{
            margin-bottom:14px;
            white-space:nowrap;
        }

        .label{
            display:inline-block;
            width:140px;
            vertical-align:top;
            font-size:12px;
        }

        .fill-line{
            flex:1;
            border-bottom:1px dotted #9b9bc7;
            min-height:20px;
            padding:0 6px 3px 6px;
            font-weight:bold;
            color:#2f2f7f;
            box-sizing:border-box;
        }

        .box-line{
            border-top:2px solid #2f2f7f;
            border-bottom:2px solid #2f2f7f;
            padding:4px 10px;
            margin-bottom:18px;
            margin-right:2cm;
            position:relative;
            box-sizing:border-box;
        }

        .box-fill{
            display:inline-block;
            border-bottom:3px solid #2f2f7f;
            margin-left:8px;
            min-width:250px;
            max-width:9cm;
            padding:0 6px 2px 6px;
            vertical-align:middle;
            font-weight:bold;
        }

        .payment-box{
            margin-top:18px;
        }

        .checkbox{
            font-size:14px;
            margin-right:15px;
        }

        .signature{
            position:absolute;
            right:2cm;
            bottom:55px;
            text-align:center;
            width:220px;
        }

        .signature-line{
            border-bottom:1px dotted #2f2f7f;
            margin-top:55px;
        }

        .watermark{
            position:absolute;
            top:45%;
            left:55%;
            transform:translate(-50%,-50%) rotate(-18deg);
            opacity:0.08;
            width:60%;
        }

        .box-text{
            display:inline-block;
            margin-left:8px;
            padding:0 6px 2px 6px;
            vertical-align:middle;
            font-weight:bold;
        }

        .form-low{
            display:flex;
            align-items:center;
            margin-bottom:14px;
        }

        .form-low .label{
            width:170px;
        }

        .form-low .colon{
            width:15px;
            text-align:center;
        }
    </style>
</head>

<body>

<div class="wrapper">

    <!--  SIDEBAR KIRI-->
    <div class="sidebar">

        <div class="sidebar-inner">
            <div class="sidebar-content">
                <div class="logo-area">
                    <img class="logo"
                         src="{{ public_path('assets/images/kwitansi.png') }}">
                </div>

                <div class="info-area">
                    <p>
                        Head Office : Jl. Kayon 24 Surabaya 60271 - Indonesia
                    </p>
                     <table class="social-row">
                        <tr>
                            <td>☎ (031) 5315678</td>
                            <td>
                                <img src="{{ public_path('assets/images/instagram.png') }}" class="icon">
                                @scomptec_learning
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <img src="{{ public_path('assets/images/facebook.png') }}" class="icon">
                                scomptec-learning
                            </td>
                            <td>
                                <img src="{{ public_path('assets/images/tik-tok.png') }}" class="icon">
                                scomptec.official
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="bottom-line"></div>

        </div>
    </div>

    <!--KONTEN KANAN-->
    <div class="content">

        <div class="title">
            KWITANSI
        </div>
            <div class="box-line">
                <span style="font-weight:bold;">No.</span>
                <span class="box-text">{{ $transaction->invoice }}</span>
            </div>
            <div class="form-low">
                <span class="label">Telah Terima Dari</span>
                <span class="colon">:</span>
                <span class="fill-line">{{ $transaction->student_name }}</span>
            </div>
            <div class="form-low">
                <span class="label">Uang Sebanyak</span>
                <span class="colon">:</span>
                <span class="fill-line">{{ ucwords(\App\Http\Controllers\PosController::terbilang($transaction->total)) }} Rupiah</span>
            </div>
            <div class="form-low">
                <span class="label">Untuk Pembayaran</span>
                <span class="colon">:</span>
                <span class="fill-line">
                    @foreach($transaction->items as $item)
                        {{ $item->product_name }} - {{ $item->class_type }}
                        @if(!$loop->last), @endif
                    @endforeach
                </span>

            </div>
            <div class="box-line" style="margin-top:28px;">
                <span style="font-style:italic;font-weight:bold;">
                    Jumlah Rp.
                </span>

                <span class="box-text amount">
                    {{ number_format($transaction->total,0,',','.') }}
                </span>
            </div>
            <div class="payment-box">
                <span class="label">Pembayaran Via</span>
                :

                <span class="checkbox">
                    {{ strtolower($transaction->payment_method) == 'cash' ? '☑' : '☐' }} Cash
                </span>

                <span class="checkbox">
                    {{ strtolower($transaction->payment_method) == 'transfer' ? '☑' : '☐' }} Transfer
                </span>
            </div>

        <div class="signature">
            Tanggal,
            {{ date('d F Y', strtotime($transaction->created_at)) }}

            <div class="signature-line"></div>
            <div style="
                margin-top:6px;
                font-size:13px;
                font-weight:bold;
                color:#2f2f7f;
                ">
                {{ $transaction->user->name }}
            </div>
        </div>
    </div>
</div>

</body>
</html>