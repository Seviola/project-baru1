@extends('layouts.app')
@section('title', 'POS Kasir')

@section('content')
<div class="row">
    <!-- LIST PRODUK -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5>Daftar Produk</h5></div>
            <div class="card-body">
                <div class="row" id="product-list">
                    {{-- Produk dari JavaScript muncul di sini --}}
                </div>
            </div>
        </div>
    </div>

    <!-- KERANJANG -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5>Keranjang</h5></div>
            <div class="card-body">
                <ul id="cart-list" class="list-group mb-3">
                    <li class="list-group-item text-center text-muted">Belum ada produk</li>
                </ul>

                <h5>Total: <span id="cart-total">Rp 0</span></h5>

                <hr>

                <label>Bayar</label>
                <input type="number" id="pay-input" class="form-control" placeholder="Masukkan uang">

                <h5 class="mt-2">Kembalian: <span id="change">Rp 0</span></h5>

                <button class="btn btn-success w-100 mt-2" onclick="payNow()">Bayar</button>
                <button class="btn btn-danger w-100 mt-2" onclick="resetCart()">Reset</button>
            </div>
        </div>
    </div>

    <!-- Tambahan -->
    <div class="row mb-3">

        <div class="col-md-4">
            <div class="card text-white" style="background-color: #8afc20;">
                <div class="card-body">
                    <h5>Pendapatan Hari Ini</h5>
                    <h3>Rp {{ number_format($totalToday) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white" style="background-color: #82f0ff;">
                <div class="card-body">
                    <h5>Sudah Disetor</h5>
                    <h3>Rp {{ number_format($alreadyDeposited) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white" style="background-color: #ff677d;">
                <div class="card-body">
                    <h5>Belum Disetor</h5>
                    <h3>Rp {{ number_format($notDeposited) }}</h3>
                </div>
            </div>
        </div>

        <button class="btn btn-warning w-100 mb-3" onclick="setorUang()">
            Setor Uang
        </button>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const products = @json($products);
    let filteredProducts = [...products];

    const productList = document.getElementById('product-list');
    const cartList = document.getElementById('cart-list');
    const cartTotal = document.getElementById('cart-total');
    const payInput = document.getElementById('pay-input');
    const changeText = document.getElementById('change');

    let cart = [];

    const searchInput = document.getElementById('search-product');

    if(searchInput){

        searchInput.addEventListener('keyup', function(){

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

    filteredProducts.forEach(function(p) {

        let imageHtml = '';

        if (p.image) {
            imageHtml = `
                <div style="height:180px; overflow:hidden;">
                    <img src="/storage/${p.image}" 
                         style="width:100%; height:100%; object-fit:cover;">
                </div>
            `;
        }

        productList.innerHTML += `
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">

                    ${imageHtml}

                    <div class="card-body d-flex flex-column text-center">

                        <h6 class="fw-bold">${p.name}</h6>

                        <small class="text-muted mb-2">
                            ${p.description ? p.description : ''}
                        </small>

                        <p class="text-secondary small">
                            Stock : ${p.stock}
                        </p>

                        <div class="mt-auto">
                            <p class="text-success fw-bold">
                                Rp ${Number(p.price).toLocaleString('id-ID')}
                            </p>

                            <button class="btn btn-primary w-100"
                                    onclick="addToCart(${p.id})"
                                    ${p.stock == 0 ? 'disabled' : ''}>
                                ${p.stock == 0 ? 'Habis' : 'Tambah'}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        `;
    });
}

    window.addToCart = function(id) {
        const product = products.find(p => p.id === id);
        const existing = cart.find(item => item.id === id);

        if(existing){

        if(existing.qty >= product.stock){
            alert("Stock tidak cukup");
            return;
        }
            existing.qty += 1;

        }else{

        if(product.stock <= 0){
            alert("Stock habis!");
            return;
        }
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                qty: 1,
                stock: product.stock 
            });
        }
        renderCart();
    }

    function renderCart() {

        cartList.innerHTML = '';
        let total = 0;

        if(cart.length === 0){
            cartList.innerHTML = 
            `<li class="list-group-item text-center text-muted">
                Belum ada produk
            </li>`;
        }

        cart.forEach((item, index) => {

            let subtotal = item.price * item.qty;
            total += subtotal;

            cartList.innerHTML += `
                <li class="list-group-item">

                    <div class="d-flex justify-content-between">
                        <strong>${item.name}</strong>
                        <strong>
                            Rp ${subtotal.toLocaleString('id-ID')}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-1">

                        <small>
                            ${item.qty} x Rp ${item.price.toLocaleString('id-ID')}
                        </small>

                        <div>
                            <button class="btn btn-sm btn-danger"
                                onclick="decreaseQty(${index})">-</button>

                            <button class="btn btn-sm btn-success"
                                onclick="increaseQty(${index})">+</button>
                        </div>

                    </div>

                </li>
            `;
        });

        cartTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    window.setorUang = function(){

    if(!confirm("Yakin ingin menyetor hari ini?")){
        return;
    }
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
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan");
    });

    }

    window.payNow = function() {
        if(cart.length === 0 ){
            alert("Keranjang masih kosong!");
            return;
        }

        let total = 0;
        let items = [];

        cart.forEach(item => {
            let subtotal = item.price * item.qty;
            total += subtotal;

            items.push({
                id: item.id,
                name: item.name,
                price: item.price,
                qty: item.qty // Sementara hanya 1, bisa dikembangkan untuk qty lebih dari 1
            });
        });

        const pay = parseInt(payInput.value) || 0;

        if(pay <= 0){
            alert('Masukkan jumlah pembayaran!');
            return;
        }

        if (pay < total) {
            alert('Uang kurang!');
            return;
        }

        const change = pay - total;

        fetch("/kasir/checkout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                total: total,
                pay: pay,
                change: change,
                items: items
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            if (!data.transaction_id) {
                alert("Transaksi gagal!");
            }

            window.location.href = "/kasir/receipt/" + data.transaction_id;
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat transaksi")
        })
    }

    window.resetCart = function() {
        cart = [];
        renderCart();
        payInput.value = '';
        changeText.innerText = 'Rp 0';
    }

    window.increaseQty = function(index){
        let item = cart[index];
        
        if(item.qty >= item.stock){
            alert("Stock tidak cukup!");
            return;
        }
        item.qty += 1;
        renderCart();
    }

    window.decreaseQty = function(index){

        if(cart[index].qty > 1){
            cart[index].qty -= 1;
        }else{
            cart.splice(index,1);
        }

        renderCart();
    }
    

    renderProducts();
});
</script>
@endsection