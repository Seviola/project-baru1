<!DOCTYPE html>
<html>
<head>
    <title>KWITANSI</title>

    <style>
        @page{
            size: 20.5cm 10.8cm;
            margin: 0;
        }

         html, body{
            width:20.5cm;
            height:10.8cm;
            margin:auto;
            padding:0;
            overflow:hidden;
            background:#d9d9d9;
            font-family:DejaVu Sans, sans-serif;
        }

        body{
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .wrapper{
            width:20.5cm;
            height:10.8cm;
            overflow:hidden;
            position:relative;
            background:#fff;
            box-shadow:0 0 8px rgba(0,0,0,0.15);
        }

        /* SIDEBAR KIRI */
        .sidebar{
            position:absolute;
            top:0;
            left:0;
            width:3.5cm;
            height:10.8cm;
            border-right:2px solid #2f2f7f;
            box-sizing:border-box;
        }

        .sidebar-inner{
            position:absolute;
            top:0;
            left:0;
            width:10.8cm;
            height:3.5cm;
            transform:rotate(-90deg) translateX(-100%);
            transform-origin:top left;

            display:flex;
            flex-direction:column;
            justify-content:center;
            padding:2px 4px;
            box-sizing:border-box;
        }

        .sidebar-content{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            height:100%;
        }

        .logo-area{
            width:11cm;
            text-align:center;
        }

        .logo{
            width:100%;
            height:auto;
            display:block;
        }

        .info-area{
            margin-top:-8px;
            padding-right:14px; /* tambah jarak dari garis */
            font-size:10px;
            line-height:1.2;
            color:#2f2f7f;
            text-align:center;
            font-weight:bold;
            box-sizing:border-box;
        }

        .social-row{
            width:100%;
            border-collapse:collapse;
            margin-top:2px;
        }

        .social-row td{
            font-size:10px;
            color:#2f2f7f;
            padding:0 6px; /* kasih jarak kanan kiri */
            line-height:1.1;
            text-align:center;
            white-space:nowrap;
        }

        .icon{
            width:10px;
            height:10px;
            vertical-align:middle;
            margin-right:3px;
        }

        /* CONTENT */
        .content{
            position:absolute;
            top:0;
            left:3.5cm;
            width:17cm;
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
            margin-bottom:20px;
            padding-right:2cm;
            letter-spacing:1px;
        }

        .box-line{
            border-top:2px solid #2f2f7f;
            border-bottom:2px solid #2f2f7f;
            padding:6px 14px;
            margin-right:2cm;
            margin-bottom:22px;
            display:flex;
            align-items:center;
        }

        .box-title{
            font-weight:bold;
            font-size:13px;
            width:120px;
        }

        .box-text{
            font-weight:bold;
            font-size:14px;
        }

        .amount{
            font-size:16px;
        }

        .form-low{
            display:flex;
            align-items:center;
            margin-bottom:16px;
            white-space:nowrap; /* supaya tidak turun */
        }

        .label{
            width:170px;
            font-size:12px;
            white-space:nowrap; /* tulisan tetap sejajar */
        }

        .colon{
            width:18px;
            text-align:center;
        }

        .fill-line{
            display:flex;
            align-items:center;
            height:22px;
            width:calc(100% - 7cm); /* paksa sama panjang */
            max-width:12cm;
            border-bottom:1px dotted #9b9bc7;
            padding:0 6px 2px 6px;
            box-sizing:border-box;
            font-weight:bold;
            font-size:13px;
            color:#2f2f7f;
            white-space:nowrap; /* jawaban tidak turun */
            overflow:hidden;
        }

        .payment-box{
            margin-top:28px;
        }

        .checkbox{
            font-size:14px;
            margin-right:28px;
        }

        .signature{
            position:absolute;
            right:2cm;
            bottom:15px; /* posisi ...... */
            text-align:center; 
            width:240px;
            color:#2f2f7f;
        }

        .signature-line{
            border-bottom:1px dotted #2f2f7f;
            margin-top:45px;
        }

        button{
            position:fixed;
            bottom:10px;
            left:10px;
            padding:8px 14px;
            border:none;
            background:#2f2f7f;
            color:#fff;
            cursor:pointer;
            border-radius:4px;
        }

        @media print{
            button{
                display:none;
            }

            body{
                margin:0;
            }
        }
    </style>
</head>

<body onload="window.print()">

<script>
window.onafterprint = function(){
    let from ="{{ request('from') }}";

    if(from === 'arsip'){
        window.location.href = "/report/transaksi";
    }else{
        window.location.href = "/kasir";
    }
}
</script>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="sidebar-inner">

            <div class="sidebar-content">

                <div class="logo-area">
                    <img class="logo"
                         src="{{ asset('assets/images/kwitansi.png') }}">
                </div>

                <div class="info-area">

                    <p>
                        Head Office : Jl. Kayon 24 Surabaya 60271 - Indonesia
                    </p>

                    <table class="social-row">
                        <tr>
                            <td>☎ (031) 5315678</td>
                            <td>
                                <img src="{{ asset('assets/images/instagram.png') }}" class="icon">
                                @scomptec_learning
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <img src="{{ asset('assets/images/facebook.png') }}" class="icon">
                                scomptec-learning
                            </td>
                            <td>
                                <img src="{{ asset('assets/images/tik-tok.png') }}" class="icon">
                                scomptec.official
                            </td>
                        </tr>
                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="title">
            KWITANSI
        </div>

        <!-- NO -->
        <div class="box-line">
            <span class="box-title">No.</span>

            <span class="box-text">
                {{ $transaction->invoice }}
            </span>
        </div>

        <!-- TELAH TERIMA -->
        <div class="form-low">
            <span class="label">Telah Terima Dari</span>

            <span class="colon">:</span>

            <span class="fill-line">
                {{ $transaction->student_name }}
            </span>
        </div>

        <!-- UANG -->
        <div class="form-low">
            <span class="label">Uang Sebanyak</span>

            <span class="colon">:</span>

            <span class="fill-line">
                {{ ucwords(\App\Http\Controllers\PosController::terbilang($transaction->total)) }} Rupiah
            </span>
        </div>

        <!-- PEMBAYARAN -->
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

        <!-- JUMLAH -->
        <div class="box-line" style="margin-top:28px;">
            <span class="box-title" style="font-style:italic;">
                Jumlah Rp.
            </span>

            <span class="box-text amount">
                {{ number_format($transaction->total,0,',','.') }}
            </span>
        </div>

        <!-- PAYMENT -->
        <div class="payment-box">

            <span class="label">Pembayaran Via</span>

            <span class="colon">:</span>

            <span class="checkbox">
                {{ strtolower($transaction->payment_method) == 'cash' ? '☑' : '☐' }} Cash
            </span>

            <span class="checkbox">
                {{ strtolower($transaction->payment_method) == 'transfer' ? '☑' : '☐' }} Transfer
            </span>

        </div>

        <!-- TTD -->
        <div class="signature">

            Surabaya,
            {{ date('d F Y', strtotime($transaction->created_at)) }}

            <div class="signature-line"></div>
            <div style="
                margin-top:6px;
                font-size:13px;
                font-weight:bold;
                color:#2f2f7f;
                ">
                ( {{ $transaction->user->name }} )
            </div>
        </div>
    </div>
</div>

<button onclick="window.print()">
    Print Ulang
</button>

</body>
</html>