@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
  <!-- Main Content (Menu List) -->
  <div class="flex-1 w-full flex flex-col gap-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <div class="bg-blue-100 text-blue-600 px-4 py-1.5 rounded-lg">
                    <i class="fa-solid fa-cash-register text-2xl"></i>
                </div>
                Kasir
            </h1>
            <p class="text-gray-500 mt-1">Pilih menu dan proses transaksi pembayaran.</p>
        </div>
    </div>

    <!-- Search / Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
      <form method="GET" action="{{ route('pos.index') }}" class="flex flex-col sm:flex-row gap-4 items-end sm:items-center">
        <div class="w-full sm:w-auto flex-1 max-w-md">
          <label for="search" class="sr-only">Cari menu</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="search" name="search" id="search" placeholder="Cari nama menu..." value="{{ request('search') }}"
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-gray-900 placeholder-gray-400 sm:text-sm transition-colors">
          </div>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2 flex-1 sm:flex-none">
            Cari
          </button>
          @if(request()->filled('search'))
          <a href="{{ route('pos.index') }}" class="bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 font-medium py-2.5 px-5 rounded-lg transition-colors flex items-center justify-center gap-2 flex-1 sm:flex-none">
            <i class="fa-solid fa-rotate-left"></i> Reset
          </a>
          @endif
        </div>
      </form>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      @forelse ($menus as $m)
      <div class="px-3 py-4 bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group"
        onclick="addToCart({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, {{ $m->stok }}, '{{ addslashes($m->category->nama_kategori) }}')">
        <div class="flex flex-col">
          <div class="flex items-center gap-2">
            <h3 class="font-bold text-gray-800 text-md leading-tight group-hover:text-blue-600 transition-colors">{{ $m->nama_menu }}</h3>
            <span class="bg-blue-50 text-blue-700 text-xs font-semibold p-1 rounded-md border border-blue-100">{{ $m->category->nama_kategori }}</span>
          </div>
          <div class="mt-auto">
            <div class="text-blue-600 font-bold text-xl mb-3">
              Rp {{ number_format($m->harga, 0, ',', '.') }}
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <div class="text-sm font-medium {{ $m->stok > 10 ? 'text-green-600' : ($m->stok > 0 ? 'text-amber-500' : 'text-red-500') }} flex items-center gap-1.5">
                    <i class="fa-solid fa-box"></i> Stok: {{ $m->stok }}
                </div>
                <button class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-span-full py-16 text-center text-gray-500 bg-white rounded-xl border border-dashed border-gray-300">
        <div class="flex flex-col items-center justify-center">
            <div class="bg-gray-100 p-4 rounded-full mb-3 text-gray-400">
                <i class="fa-solid fa-utensils text-3xl"></i>
            </div>
            <p class="text-lg font-medium text-gray-700">Belum ada menu</p>
            <p class="text-sm mt-1">Menu tidak ditemukan atau stok habis.</p>
        </div>
      </div>
      @endforelse
    </div>
  </div>

  <!-- Sidebar (Cart) -->
  <div class="w-full lg:w-[400px] xl:w-[450px] shrink-0">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 sticky top-6 flex flex-col h-[calc(100vh-3rem)] max-h-[800px]">
      <div class="p-5 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-shopping-cart text-blue-600"></i> Pesanan Saat Ini
        </h2>
      </div>

      <!-- Cart Items -->
      <div class="flex-1 overflow-y-auto p-5" id="cart-items">
        <p class="text-center text-gray-400 py-10 flex flex-col items-center gap-3">
          <i class="fa-solid fa-basket-shopping text-4xl opacity-50"></i>
          <span>Keranjang masih kosong</span>
        </p>
      </div>

      <!-- Cart Total & Checkout -->
      <div class="p-5 border-t border-gray-100 bg-gray-50/80 rounded-b-xl">
        <div class="flex justify-between items-center mb-4">
          <span class="text-gray-600 font-medium">Total Tagihan</span>
          <span id="cart-total-price" class="text-2xl font-bold text-blue-700">Rp 0</span>
        </div>
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2" onclick="checkout()">
            <i class="fa-solid fa-check-double"></i> Proses Pembayaran
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Custom Modal -->
<div id="custom-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-opacity duration-300">
  <div class="w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 text-center shadow-2xl transition-all scale-95 duration-300">
    <div id="modal-icon-container" class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-600">
      <i id="modal-icon" class="fa-solid fa-circle-info text-2xl"></i>
    </div>
    <h3 id="modal-title" class="text-xl font-bold text-gray-900 mb-2">Notifikasi</h3>
    <p id="modal-message" class="text-sm text-gray-600 mb-6 leading-relaxed">
      Pesan di sini...
    </p>
    <div id="modal-actions" class="flex justify-center gap-3 w-full">
      <button id="modal-btn-cancel" class="hidden flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
      <button id="modal-btn-ok" class="flex-1 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">Oke</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<style>
/* Custom Scrollbar for Cart */
#cart-items::-webkit-scrollbar {
    width: 6px;
}
#cart-items::-webkit-scrollbar-track {
    background: transparent;
}
#cart-items::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 10px;
}
</style>
<script>
  let cart = {};
  let blockCheckout = false;

  // --- ENGINE MODAL CUSTOM ---
  function showModal( {
    title, message, type = 'info', showCancel = false, onConfirm = null
  }) {
    const modal = document.getElementById('custom-modal');
    const modalBox = modal.querySelector('div.max-w-sm');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalIconContainer = document.getElementById('modal-icon-container');
    const modalIcon = document.getElementById('modal-icon');
    const btnOk = document.getElementById('modal-btn-ok');
    const btnCancel = document.getElementById('modal-btn-cancel');

    // Reset Classes & Dynamic Styling
    modalIconContainer.className = "mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full";
    btnOk.className = "flex-1 px-5 py-2.5 text-sm font-bold text-white rounded-xl shadow-sm transition-colors";

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
    const modalBox = modal.querySelector('div.max-w-sm');
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
      '<p class="text-center text-gray-400 py-10 flex flex-col items-center gap-3"><i class="fa-solid fa-basket-shopping text-4xl opacity-50"></i><span>Keranjang masih kosong</span></p>';
    } else {
        const listWrapper = document.createElement('div');
        listWrapper.className = 'flex flex-col gap-3';

        for (let id in cart) {
            const item = cart[id];
            const subtotal = item.price * item.qty;
            total += subtotal;

            listWrapper.innerHTML += `
            <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-3 hover:border-blue-200 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm leading-tight mb-0.5">${item.name}</h4>
                        <span class="text-xs text-gray-500">${item.category}</span>
                    </div>
                    <div class="font-bold text-blue-600 text-sm whitespace-nowrap ml-2">${formatRupiah(item.price)}</div>
                </div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-50">
                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                        <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors" onclick="updateQty(${id}, -1)" title="Kurangi">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                        <input type="number" class="w-10 h-8 text-center text-sm font-medium bg-transparent border-none focus:ring-0 text-gray-800 p-0" value="${item.qty}" min="1" max="${item.max}" onchange="setQty(${id}, this.value)" onclick="event.stopPropagation();">
                        <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors" onclick="updateQty(${id}, 1)" title="Tambah">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>
                    <button class="w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center" onclick="removeFromCart(${id})" title="Hapus">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>
            `;
        }
        container.appendChild(listWrapper);
    }

    const totalElement = document.getElementById('cart-total-price');
    totalElement.innerText = formatRupiah(total);
  }

  function checkout() {
    const cartArray = Object.values(cart);
    if (cartArray.length === 0) {
      showModal( {
        title: 'Keranjang Kosong', message: 'Pilih menu terlebih dahulu.', type: 'info'
      });
      return;
    }

    if (blockCheckout) {
      return;
    }

    // Mengganti confirm() bawaan dengan modal konfirmasi kustom yang memiliki tombol Batal & Oke
    showModal( {
      title: 'Konfirmasi Pembayaran',
      message: 'Apakah Anda yakin ingin memproses pembayaran untuk ' + cartArray.length + ' jenis item?',
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
    title: 'Pembayaran Berhasil',
    message: data.message,
    type: 'success',
    onConfirm: () => { window.location.reload(); }
    });
    } else {
    showModal({ title: 'Gagal', message: 'Gagal: ' + data.message, type: 'error' });
    }
    })
    .catch(err => {
    showModal({ title: 'Error Sistem', message: 'Terjadi kesalahan sistem.', type: 'error' });
    console.error(err);
    });
  }
</script>
@endpush
