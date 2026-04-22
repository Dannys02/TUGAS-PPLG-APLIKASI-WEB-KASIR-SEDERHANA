@extends('layouts.app')

@section('content')
<div class="pos-container">
    <div>
        <h1 class="header-title">Kasir (Point of Sale)</h1>
        <div class="menu-grid">
            @foreach($menus as $m)
            <div class="menu-card" onclick="addToCart({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, {{ $m->stok }})">
                <h3>{{ $m->nama_menu }}</h3>
                <div class="menu-price">Rp {{ number_format($m->harga, 0, ',', '.') }}</div>
                <div class="menu-stock"><i class="fa-solid fa-box"></i> Stok: {{ $m->stok }}</div>
            </div>
            @endforeach
            @if($menus->isEmpty())
                <p style="color: var(--text-muted);">Belum ada menu yang tersedia atau stok habis.</p>
            @endif
        </div>
    </div>

    <div class="cart-container">
        <h2 style="color: var(--primary-dark); border-bottom: 2px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 0;">Keranjang Pesanan</h2>
        <div class="cart-items" id="cart-items">
            <p style="text-align: center; color: var(--text-muted); margin-top: 2rem;">Keranjang kosong</p>
        </div>
        <div class="cart-total">
            <div class="cart-total-row">
                <span>Total:</span>
                <span id="cart-total-price">Rp 0</span>
            </div>
            <button class="btn btn-primary btn-checkout" onclick="checkout()"><i class="fa-solid fa-check-double"></i> Proses Pembayaran</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = {};

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function addToCart(id, name, price, stock) {
        if(cart[id]) {
            if(cart[id].qty < stock) {
                cart[id].qty++;
            } else {
                alert('Stok tidak mencukupi!');
            }
        } else {
            if(stock > 0) {
                cart[id] = { id: id, name: name, price: price, qty: 1, max: stock };
            } else {
                alert('Stok habis!');
            }
        }
        renderCart();
    }

    function updateQty(id, delta) {
        if(!cart[id]) return;

        cart[id].qty += delta;

        if(cart[id].qty > cart[id].max) {
            cart[id].qty = cart[id].max;
            alert('Stok tidak mencukupi!');
        }

        if(cart[id].qty <= 0) {
            delete cart[id];
        }

        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        container.innerHTML = '';
        let total = 0;

        const cartKeys = Object.keys(cart);
        if(cartKeys.length === 0) {
            container.innerHTML = '<p style="text-align: center; color: var(--text-muted); margin-top: 2rem;">Keranjang kosong</p>';
        }

        for(let id in cart) {
            const item = cart[id];
            const subtotal = item.price * item.qty;
            total += subtotal;

            container.innerHTML += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <div class="cart-item-price">${formatRupiah(item.price)}</div>
                    </div>
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="updateQty(${id}, -1)"><i class="fa-solid fa-minus"></i></button>
                        <span style="font-weight: bold; width: 20px; text-align: center;">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${id}, 1)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            `;
        }

        document.getElementById('cart-total-price').innerText = formatRupiah(total);
    }

    function checkout() {
        const cartArray = Object.values(cart);
        if(cartArray.length === 0) {
            alert('Keranjang masih kosong!');
            return;
        }

        fetch('{{ route("pos.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ cart: cartArray })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                cart = {};
                window.location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan sistem.');
            console.error(err);
        });
    }
</script>
@endpush
