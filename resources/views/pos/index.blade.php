@extends('layouts.app')

@section('content')
<div class="pos-container">
  <div>
    <h1 class="header-title"><i class="fa-solid fa-cash-register"></i> Kasir (Point of Sale)</h1>

    <div class="card">
      <form method="GET" action="{{ route('pos.index') }}"
        style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1rem; flex-direction: column; align-items: flex-start;">
          <input type="search" name="search" placeholder="🔍 Cari nama menu..." value="{{ request('search') }}"
          class="form-control" style="max-width: 400px;">
          <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search"></i> Cari
            </button>

            @if(request()->filled('search'))
            <a href="{{ route('pos.index') }}" class="btn btn-danger">
              <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
            @endif
          </div>
        </div>
      </form>
    </div>

    <div class="menu-grid">
      @foreach ($menus as $m)
      <div class="menu-card"
        onclick="addToCart({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, {{ $m->stok }}, '{{ addslashes($m->category->nama_kategori) }}')">
        <h3>{{ $m->nama_menu }}</h3>
        <div class="menu-price">
          Rp {{ number_format($m->harga, 0, ',', '.') }}
        </div>
        <div class="flex items-center justify-between flex-col gap-2">
          <div class="menu-stock">
            <i class="fa-solid fa-tags"></i> {{ $m->category->nama_kategori }}
          </div>
          <div class="menu-stock" style="color: var(--success); font-weight: 600;">
            <i
              class="fa-solid fa-box"></i> Stok: {{ $m->stok }}
          </div>
        </div>
      </div>
      @endforeach
      @if ($menus->isEmpty())
      <p style="color: var(--text-muted); text-align: center; grid-column: 1/-1;">
        Belum ada menu yang tersedia
        atau stok habis.
      </p>
      @endif
    </div>
  </div>

  <div class="cart-container">
    <h2><i class="fa-solid fa-shopping-cart"></i> Keranjang Pesanan</h2>
    <div class="cart-items" id="cart-items">
      <p style="text-align: center; color: var(--text-muted); padding: 2rem 0;">
        Keranjang kosong
      </p>
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

<div id="custom-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black bg-opacity-50 p-4 transition-opacity duration-300">
  <div class="w-full max-w-sm transform overflow-hidden rounded-xl bg-white p-6 text-center shadow-2xl transition-all scale-95 duration-300">
    <div id="modal-icon-container" class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-600">
      <i id="modal-icon" class="fa-solid fa-circle-info text-2xl"></i>
    </div>
    <h3 id="modal-title" class="text-lg font-bold text-gray-900 mb-2">Notifikasi</h3>
    <p id="modal-message" class="text-sm text-gray-500 mb-6 leading-relaxed">
      Pesan di sini...
    </p>
    <div id="modal-actions" class="flex justify-center gap-3">
      <button id="modal-btn-cancel" class="hidden px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
      <button id="modal-btn-ok" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">Oke</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let cart = {};
  let blockCheckout = false;

  // --- ENGINE MODAL CUSTOM ---
  function showModal( {
    title, message, type = 'info', showCancel = false, onConfirm = null
  }) {
    const modal = document.getElementById('custom-modal');
    const modalBox = modal.querySelector('div');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalIconContainer = document.getElementById('modal-icon-container');
    const modalIcon = document.getElementById('modal-icon');
    const btnOk = document.getElementById('modal-btn-ok');
    const btnCancel = document.getElementById('modal-btn-cancel');

    // Reset Classes & Dynamic Styling
    modalIconContainer.className = "mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full";
    btnOk.className = "px-5 py-2 text-sm font-medium text-white rounded-lg shadow-sm transition-colors";

    if (type === 'error') {
      modalIconContainer.classList.add('bg-red-100', 'text-red-600');
      modalIcon.className = "fa-solid fa-circle-xmark text-2xl";
      btnOk.classList.add('bg-red-600', 'hover:bg-red-700');
    } else if (type === 'success') {
      modalIconContainer.classList.add('bg-green-100', 'text-green-600');
      modalIcon.className = "fa-solid fa-circle-check text-2xl";
      btnOk.classList.add('bg-green-600', 'hover:bg-green-700');
    } else if (type === 'confirm') {
      modalIconContainer.classList.add('bg-amber-100', 'text-amber-600');
      modalIcon.className = "fa-solid fa-circle-question text-2xl";
      btnOk.classList.add('bg-amber-600', 'hover:bg-amber-700');
    } else {
      modalIconContainer.classList.add('bg-blue-100', 'text-blue-600');
      modalIcon.className = "fa-solid fa-circle-info text-2xl";
      btnOk.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }

    modalTitle.innerText = title;
    modalMessage.innerText = message;

    // Toggle Cancel Button
    if (showCancel) {
      btnCancel.classList.remove('hidden');
    } else {
      btnCancel.classList.add('hidden');
    }

    // Show Modal with smooth transition
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
    modal.classList.add('opacity-100');
    modalBox.classList.remove('scale-95');
    modalBox.classList.add('scale-100');
    }, 10);

    // Clean up event listeners to prevent duplication
    const cloneOk = btnOk.cloneNode(true);
    const cloneCancel = btnCancel.cloneNode(true);
    btnOk.parentNode.replaceChild(cloneOk, btnOk);
    btnCancel.parentNode.replaceChild(cloneCancel, btnCancel);

    // Handle Confirm Action
    cloneOk.addEventListener('click', () => {
    closeModal();
    if (onConfirm) onConfirm();
    });

    // Handle Cancel Action
    cloneCancel.addEventListener('click', closeModal);
  }

  function closeModal() {
    const modal = document.getElementById('custom-modal');
    const modalBox = modal.querySelector('div');
    modal.classList.remove('opacity-100');
    modalBox.classList.remove('scale-100');
    modalBox.classList.add('scale-95');
    setTimeout(() => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    }, 150);
  }
  // ---------------------------

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
        showModal( {
          title: 'Stok Terbatas', message: 'Stok tidak mencukupi!', type: 'error'
        });
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
        showModal( {
          title: 'Stok Habis', message: 'Stok habis!', type: 'error'
        });
      }
    }
    renderCart();
  }

  function updateQty(id, delta) {
    if (!cart[id]) return;

    cart[id].qty += delta;

    if (cart[id].qty > cart[id].max) {
      cart[id].qty = cart[id].max;
      showModal( {
        title: 'Stok Terbatas', message: 'Stok tidak mencukupi!', type: 'error'
      });
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

      blockCheckout = true;
      showModal( {
        title: 'Peringatan Stok',
        message: 'Stok tidak mencukupi! Stok maksimal: ' + cart[id].max,
        type: 'error'
      });

      setTimeout(() => {
      blockCheckout = false;
      }, 300);

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
      <div style="display: flex; align-items: center; gap: 0.5rem;">
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

    const totalElement = document.getElementById('cart-total-price');
    totalElement.innerText = formatRupiah(total);
  }

  function checkout() {
    const cartArray = Object.values(cart);
    if (cartArray.length === 0) {
      showModal( {
        title: 'Keranjang Kosong', message: 'Keranjang masih kosong!', type: 'info'
      });
      return;
    }

    if (blockCheckout) {
      return;
    }

    // Mengganti confirm() bawaan dengan modal konfirmasi kustom yang memiliki tombol Batal & Oke
    showModal( {
      title: 'Konfirmasi Pembayaran',
      message: 'Apakah Anda yakin ingin memproses pembayaran ini?',
      type: 'confirm',
      showCancel: true,
      onConfirm: () => {
        executeCheckout(cartArray);
      }
    });
  }

  function executeCheckout(cartArray) {
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
    showModal({
    title: 'Berhasil',
    message: data.message,
    type: 'success',
    onConfirm: () => { window.location.reload(); }
    });
    } else {
    showModal({ title: 'Gagal', message: 'Gagal: ' + data.message, type: 'error' });
    }
    })
    .catch(err => {
    showModal({ title: 'Error', message: 'Terjadi kesalahan sistem.', type: 'error' });
    console.error(err);
    });
  }
</script>
@endpush