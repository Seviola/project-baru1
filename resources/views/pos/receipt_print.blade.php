<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi</title>

    <style>
        body {
            margin: 0;
            font-family: "Times New Roman", serif;
        }

        .paper {
            width: 1000px;
            height: 450px;
            margin: auto;
            position: relative;
        }

        /* BACKGROUND IMAGE */
        .bg {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }

        /* TEXT OVERLAY */
        .text {
            position: absolute;
            z-index: 2;
            font-size: 16px;
            color: #000;
        }

        .bold {
            font-weight: bold;
        }

        /* POSISI TEXT (atur sesuai gambar kamu) */
        .no        { top: 85px; left: 300px; }
        .nama      { top: 130px; left: 300px; }
        .uang      { top: 175px; left: 300px; width: 600px; }
        .untuk     { top: 235px; left: 300px; width: 600px; }
        .jumlah    { top: 310px; left: 300px; font-size: 20px; font-weight: bold; }
        .metode    { top: 350px; left: 300px; }

        .tanggal   { top: 300px; right: 80px; }

        @media print {
            button { display: none; }
        }
    </style>
</head>

<body onload="window.print()">

<script>
window.onafterprint = function(){
    window.location.href="/kasir";
}
</script>

<div class="paper">

    <!-- BACKGROUND -->
    <img src="{{ asset('assets/images/Scomptec.png') }}" class="bg">

    <!-- DATA -->
    <div class="text no">
        {{ $transaction->invoice }}
    </div>

    <div class="text nama">
        {{ $transaction->student_name }}
    </div>

    <div class="text uang">
        {{ number_format($transaction->total,0,',','.') }} Rupiah
    </div>

    <div class="text untuk">
        @foreach($transaction->items as $item)
            {{ $item->product_name }} x{{ $item->qty }}@if(!$loop->last), @endif
        @endforeach
    </div>

    <div class="text jumlah">
        Rp {{ number_format($transaction->total,0,',','.') }}
    </div>

    <div class="text metode">
        {{ $transaction->payment_method }}
    </div>

    <div class="text tanggal">
        {{ date('d F Y', strtotime($transaction->created_at)) }}
    </div>

</div>

<center>
    <button onclick="window.print()">Print Ulang</button>
</center>

</body>
</html>