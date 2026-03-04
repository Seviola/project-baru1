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
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const products = @json($products);

    const productList = document.getElementById('product-list');
    const cartList = document.getElementById('cart-list');
    const cartTotal = document.getElementById('cart-total');
    const payInput = document.getElementById('pay-input');
    const changeText = document.getElementById('change');

    let cart = [];

    function renderProducts() {
    productList.innerHTML = '';

    products.forEach(function(p) {

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

                        <div class="mt-auto">
                            <p class="text-success fw-bold">
                                Rp ${Number(p.price).toLocaleString('id-ID')}
                            </p>

                            <button class="btn btn-primary w-100"
                                    onclick="addToCart(${p.id})">
                                Tambah
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
        cart.push(product);
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

        cart.forEach((item) => {
            total += item.price;
            cartList.innerHTML += `
                <li class="list-group-item d-flex justify-content-between">
                    ${item.name}
                    <span>Rp ${Number(item.price).toLocaleString('id-ID')}</span>
                </li>
            `;
        });

        cartTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    window.payNow = function() {
        let total = 0;
        let items = [];

        cart.forEach(item => {
            total += item.price;

            items.push({
                id: item.id,
                name: item.name,
                price: item.price,
                qty: 1 // Sementara hanya 1, bisa dikembangkan untuk qty lebih dari 1
            });
        });

        const pay = parseInt(payInput.value) || 0;

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
        });
    }

    window.resetCart = function() {
        cart = [];
        renderCart();
        payInput.value = '';
        changeText.innerText = 'Rp 0';
    }
    

    renderProducts();
});
</script>
@endsection