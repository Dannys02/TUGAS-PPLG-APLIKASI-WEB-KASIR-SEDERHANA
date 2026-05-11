@extends('layouts.app')

@section('content')
    <div class="pos-container">
        <div>
            <h1 class="header-title"><i class="fa-solid fa-cash-register"></i> Kasir (Point of Sale)</h1>
            <div class="menu-grid">
                @foreach ($menus as $m)
                    <div class="menu-card"
                        onclick="addToCart({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, {{ $m->stok }}, '{{ addslashes($m->category->nama_kategori) }}')">
                        <h3>{{ $m->nama_menu }}</h3>
                        <div class="menu-price">Rp {{ number_format($m->harga, 0, ',', '.') }}</div>
                        <div class="flex items-center justify-between flex-col gap-2">
                            <div class="menu-stock"><i class="fa-solid fa-tags"></i> {{ $m->category->nama_kategori }}</div>
                            <div class="menu-stock" style="color: var(--success); font-weight: 600;"><i
                                    class="fa-solid fa-box"></i> Stok: {{ $m->stok }}</div>
                        </div>
                    </div>
                @endforeach
                @if ($menus->isEmpty())
                    <p style="color: var(--text-muted); text-align: center; grid-column: 1/-1;">Belum ada menu yang tersedia
                        atau stok habis.</p>
                @endif
            </div>
        </div>

        <div class="cart-container">
            <h2><i class="fa-solid fa-shopping-cart"></i> Keranjang Pesanan</h2>
            <div class="cart-items" id="cart-items">
                <p style="text-align: center; color: var(--text-muted); padding: 2rem 0;">Keranjang kosong</p>
            </div>
            <div class="cart-total">
                <div class="cart-total-row">
                    <span>Total Harga:</span>
                    <span id="cart-total-price">Rp 0</span>
                </div>
                <button class="btn btn-primary btn-checkout" onclick="checkout()"><i class="fa-solid fa-check-double"></i>
                    Proses Pembayaran</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let cart = {};

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function addToCart(id, name, price, stock, category) {
            if (cart[id]) {
                if (cart[id].qty < stock) {
                    cart[id].qty++;
                } else {
                    alert('Stok tidak mencukupi!');
                }
            } else {
                if (stock > 0) {
                    cart[id] = {
                        id: id,
                        name: name,
                        price: price,
                        qty: 1,
                        max: stock,
                        category: category
                    };
                } else {
                    alert('Stok habis!');
                }
            }
            renderCart();
        }

        function updateQty(id, delta) {
            if (!cart[id]) return;

            cart[id].qty += delta;

            if (cart[id].qty > cart[id].max) {
                cart[id].qty = cart[id].max;
                alert('Stok tidak mencukupi!');
            }

            if (cart[id].qty <= 0) {
                delete cart[id];
            }

            renderCart();
        }

        function setQty(id, newQty) {
            if (!cart[id]) return;

            newQty = parseInt(newQty);
            if (isNaN(newQty) || newQty < 1) {
                cart[id].qty = 1;
            } else if (newQty > cart[id].max) {
                cart[id].qty = cart[id].max;
                alert('Stok tidak mencukupi! Maksimal: ' + cart[id].max);
            } else {
                cart[id].qty = newQty;
            }

            renderCart();
        }

        function removeFromCart(id) {
            delete cart[id];
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            container.innerHTML = '';
            let total = 0;

            const cartKeys = Object.keys(cart);
            if (cartKeys.length === 0) {
                container.innerHTML =
                    '<p style="text-align: center; color: var(--text-muted); padding: 2rem 0;">Keranjang kosong</p>';
            }

            for (let id in cart) {
                const item = cart[id];
                const subtotal = item.price * item.qty;
                total += subtotal;

                container.innerHTML += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="flex items-center justify-between">
                        <h4>${item.name}</h4>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.3rem;">${item.category}</div>
                        </div>
                        <div class="cart-item-price">${formatRupiah(item.price)}</div>
                    </div>
                    <div style="flex: 0 0 auto; display: flex; align-items: center; gap: 0.5rem;">
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty(${id}, -1)" title="Kurangi"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" class="qty-input" value="${item.qty}" min="1" max="${item.max}" onchange="setQty(${id}, this.value)" onclick="event.stopPropagation();">
                            <button class="qty-btn" onclick="updateQty(${id}, 1)" title="Tambah"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${id})" style="width: 100%; margin-top: 0.3rem;"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `;
            }

            // Update total
            const totalElement = document.getElementById('cart-total-price');
            totalElement.innerText = formatRupiah(total);
        }

        function checkout() {
            const cartArray = Object.values(cart);
            if (cartArray.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            fetch('{{ route('pos.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cart: cartArray
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
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
