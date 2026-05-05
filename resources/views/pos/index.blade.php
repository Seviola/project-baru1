@extends('layouts.app')
@section('title', 'POS Kasir')

@section('content')
<div class="row">

    {{-- LIST DATA KELAS --}}
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Daftar Kelas Kursus</h5>
            </div>
            <div class="card-body">
                <div class="row" id="product-list">
                    {{-- muncul dari JS --}}
                </div>
            </div>
        </div>
    </div>

    {{-- KWITANSI PEMBAYARAN DIGITAL --}}
    <div class="col-md-4">
        <div class="card shadow border-sm" style="border:2px solid #1d2f6f">

            <div class="card-header text-center bg-white border-bottom">
                <h4 class="fw-bold text-primary mb-0" style="letter-spacing:2px;">
                    PEMBAYARAN
                </h4>
                <small class="text-muted">PT Scomptec Edukom Persada</small>
            </div>

            <div class="card-body" style="font-size:14px; line-height:28px;">

                <div class="mb-3">
                    <strong>No. :</strong>
                    <span>
                        KW-{{ date('Ymd') }}-
                        <span id="invoice-no">{{ rand(100,999) }}</span>
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Telah Terima Dari :</strong>
                    <input type="text" id="student-name" class="form-control mt-1"
                        placeholder="Masukkan nama siswa / wali murid">
                </div>

                <div class="mb-4">
                    <strong>Uang Sebanyak :</strong>
                    <ul class="list-group mt-2">
                        <li id="money-spell" class="list-group-item text-center text-muted">
                            Belum ada nominal pembayaran
                        </li>
                    </ul>
                </div>

                <div class="mb-4">
                    <strong>Untuk Pembayaran :</strong>
                    <ul class="list-group mt-2" id="cart-list">
                        <li class="list-group-item text-center text-muted">
                            Belum ada kelas dipilih
                        </li>
                    </ul>
                </div>

                <div class="mb-3">
                    <strong>Jumlah Rp :</strong>
                    <h5 class="text-success fw-bold mt-2" id="cart-total">Rp 0</h5>
                </div>

                <div class="mb-3">
                    <strong>Pembayaran Via :</strong><br>
                    <label class="me-3">
                        <input type="radio" name="payment_method" value="Cash" checked> Cash
                    </label>
                    <label>
                        <input type="radio" name="payment_method" value="Transfer"> Transfer
                    </label>
                </div>

                <div class="mb-3">
                    <strong>Bayar :</strong>
                    <input type="number" id="pay-input" class="form-control mt-1"
                        placeholder="Masukkan nominal bayar">
                </div>

                <div class="text-end mt-5">
                    <p>Tanggal, {{ date('d F Y') }}</p>

                    <button class="btn btn-primary w-100 mt-2" onclick="payNow()">
                        Bayar Sekarang
                    </button>

                    <button class="btn btn-danger w-100 mt-2" onclick="resetCart()">
                        Reset
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- INFO SETORAN --}}
    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card text-dark shadow" style="background:#d9ff8f;">
                <div class="card-body">
                    <h5>Pendapatan Hari Ini</h5>
                    <h3>Rp {{ number_format($totalToday) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark shadow" style="background:#b5f3ff;">
                <div class="card-body">
                    <h5>Sudah Disetor</h5>
                    <h3>Rp {{ number_format($alreadyDeposited) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark shadow" style="background:#ffb6c1;">
                <div class="card-body">
                    <h5>Belum Disetor</h5>
                    <h3>Rp {{ number_format($notDeposited) }}</h3>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button class="btn btn-outline-warning" onclick="setorUang()">
                Setor Uang
            </button>

            <a href="/report/setoran" class="btn btn-outline-info">
                Report Setoran Saya
            </a>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const products = @json($products);
    let filteredProducts = [...products];

    const productList = document.getElementById('product-list');
    const cartTotal  = document.getElementById('cart-total');
    const payInput   = document.getElementById('pay-input');

    let cart = [];

    const searchInput = document.getElementById('search-product');

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            let keyword = this.value.toLowerCase();

            filteredProducts = products.filter(p =>
                p.name.toLowerCase().includes(keyword) ||
                (p.barcode && p.barcode.toLowerCase().includes(keyword))
            );

            renderProducts();
        });
    }

    function renderProducts() {
        productList.innerHTML = '';

        filteredProducts.forEach(function (p) {
            productList.innerHTML += `
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow border-0">
                        <div class="card-body">

                            <h5 class="fw-bold text-primary">${p.name}</h5>

                            <p class="mb-1">
                                <strong>Kode:</strong> ${p.barcode}
                            </p>

                            <p class="mb-1">
                                <strong>Biaya Kursus:</strong>
                                Rp ${Number(p.purchase_price).toLocaleString('id-ID')}
                            </p>

                            <p class="mb-1">
                                <strong>Ruang Kelas:</strong> ${p.price}
                            </p>

                            <p class="text-muted small">
                                ${p.description ? p.description : ''}
                            </p>

                            <button class="btn btn-warning w-100 mt-2"
                                onclick="addToCart(${p.id})">
                                Tambah ke Keranjang
                            </button>

                        </div>
                    </div>
                </div>
            `;
        });
    }

    window.addToCart = function (id) {
        const product  = products.find(p => p.id === id);
        const existing = cart.find(item => item.id === id);

        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: parseInt(product.purchase_price),
                qty: 1
            });
        }

        renderCart();
    }

    window.increaseQty = function(index){
        cart[index].qty += 1;
        renderCart();
    }

    window.decreaseQty = function(index){
        if(cart[index].qty > 1){
            cart[index].qty -= 1;
        } else {
            cart.splice(index,1);
        }
        renderCart();
    }

        function renderCart() {
            let total = 0;
            let html = '';

            if (cart.length === 0) {
                html = `<li class="list-group-item text-center text-muted">
                            Belum ada kelas dipilih
                        </li>`;
            } else {
                cart.forEach((item, index) => {
                    let subtotal = parseInt(item.price) * parseInt(item.qty);
                    total += subtotal;

                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            
                            <div>
                                <strong>${item.name}</strong><br>
                                <small>Rp ${item.price.toLocaleString('id-ID')}</small>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-danger btn-sm"
                                        onclick="decreaseQty(${index})">-</button>

                                <span>${item.qty}</span>

                                <button class="btn btn-success btn-sm"
                                        onclick="increaseQty(${index})">+</button>
                            </div>

                        </li>
                    `;
                });
            }

            document.getElementById('cart-list').innerHTML = html;

            document.getElementById('cart-total').innerText =
                'Rp ' + total.toLocaleString('id-ID');

            document.getElementById('money-spell').innerHTML =
                total > 0 ? terbilang(total) + ' rupiah' : 'Belum ada nominal pembayaran';
        }

    function terbilang(nilai) {
        const huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima",
                       "Enam", "Tujuh", "Delapan", "Sembilan",
                       "Sepuluh", "Sebelas"];

        if (nilai < 12) return huruf[nilai];
        else if (nilai < 20) return terbilang(nilai - 10) + " Belas";
        else if (nilai < 100) return terbilang(Math.floor(nilai / 10)) + " Puluh " + terbilang(nilai % 10);
        else if (nilai < 200) return "Seratus " + terbilang(nilai - 100);
        else if (nilai < 1000) return terbilang(Math.floor(nilai / 100)) + " Ratus " + terbilang(nilai % 100);
        else if (nilai < 2000) return "Seribu " + terbilang(nilai - 1000);
        else if (nilai < 1000000) return terbilang(Math.floor(nilai / 1000)) + " Ribu " + terbilang(nilai % 1000);
        else if (nilai < 1000000000) return terbilang(Math.floor(nilai / 1000000)) + " Juta " + terbilang(nilai % 1000000);

        return "";
    }

    window.resetCart = function () {
        cart = [];
        renderCart();
        payInput.value = '';
        document.getElementById('student-name').value = '';
    }

    window.setorUang = function () {
        if (!confirm("Yakin ingin menyetor hari ini?")) return;

        fetch("/kasir/setor", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
    }

    window.payNow = function () {
        if (cart.length === 0) {
            alert("Keranjang kosong");
            return;
        }

        let studentName = document.getElementById('student-name').value;
        if(studentName == ''){
            alert("Nama siswa / wali murid wajib diisi");
            return;
        }

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        let total = 0;
        let items = [];

        cart.forEach(item => {
            total += parseInt(item.price) * parseInt(item.qty);

            items.push({
                id: item.id,
                name: item.name,
                price: parseInt(item.price),
                qty: parseInt(item.qty)
            });
        });

        let pay = parseInt(payInput.value.replace(/\./g,'')) || 0;

        if (pay < total) {
            alert("Nominal bayar kurang");
            return;
        }

        let change = pay - total;

        fetch("/kasir/checkout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                student_name: studentName,
                payment_method: paymentMethod,
                total: total,
                pay: pay,
                change: change,
                items: items
            })
        })
        .then(res => res.json())
        .then(data => {

            console.log(data);

            if(data.error){
                alert(data.error);
                return;
            }

            if(data.transaction_id){
                window.location.href = "/kasir/receipt/" + data.transaction_id;
            }else{
                alert("Transaksi gagal tanpa pesan");
            }

        })
        .catch(err => {
            console.log(err);
            alert("Terjadi kesalahan checkout");
        });
    }

    renderProducts();

    payInput.addEventListener('keyup', function () {
        let total = 0;
        cart.forEach(item => total += item.price * item.qty);

        let bayar = parseInt(this.value) || 0;
        let kembali = bayar - total;

        document.getElementById('change').innerHTML =
            kembali > 0 ? 'Rp ' + kembali.toLocaleString('id-ID') : 'Rp 0';
    });

});
</script>
@endsection