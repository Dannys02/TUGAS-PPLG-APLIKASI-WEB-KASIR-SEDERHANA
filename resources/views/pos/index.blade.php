@extends('layouts.app')

@section('content')
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 w-full flex flex-col gap-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                        <i class="fa-solid fa-cash-register text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Aplikasi Kasir</h1>
                        <p class="text-sm text-slate-500">Pilih menu pesanan pelanggan, atur kuantitas item, dan proses
                            transaksi kilat</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <form method="GET" action="{{ route('pos.index') }}"
                    class="flex flex-col sm:flex-row gap-3 items-end sm:items-center">
                    <div class="w-full sm:w-auto flex-1 max-w-md">
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-slate-400 text-sm">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="search" name="search" id="search"
                                placeholder="Cari nama makanan atau minuman..." value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-3 px-5 rounded-xl shadow-sm transition-colors flex-1 sm:flex-none">
                            Cari Menu
                        </button>
                        @if (request()->filled('search'))
                            <a href="{{ route('pos.index') }}"
                                class="bg-red-50 hover:bg-red-100 border border-red-100 text-red-600 text-xs font-semibold py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-1.5 flex-1 sm:flex-none">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse ($menus as $m)
                    <div id="menu-card-{{ $m->id }}" data-stok="{{ $m->stok }}"
                        class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all cursor-pointer group flex flex-col h-full justify-between"
                        onclick="addToCart({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, this.getAttribute('data-stok'), '{{ addslashes($m->category->nama_kategori) }}')">
                        <div>
                            <div class="flex flex-col gap-2">
                                <span
                                    class="w-fit px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100/50 uppercase tracking-wide shrink-0">
                                    {{ $m->category->nama_kategori ?? 'Umum' }}
                                </span>
                                <h3 class="font-bold text-slate-700 text-sm md:text-base leading-snug group-hover:text-blue-600 transition-colors"
                                    title="{{ $m->nama_menu }}">
                                    {{ $m->nama_menu }}
                                </h3>
                            </div>

                            <div class="text-slate-800 font-extrabold text-lg md:text-xl tracking-tight mb-4">
                                <span
                                    class="text-xs font-normal text-slate-400 mr-0.5">Rp</span>{{ number_format($m->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-auto">
                            <div id="menu-stok-{{ $m->id }}" class="text-xs font-bold flex items-center gap-1">
                                @if ($m->stok > 10)
                                    <span class="text-green-600 flex items-center gap-1"><span
                                            class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Stok:
                                        {{ $m->stok }}</span>
                                @elseif($m->stok > 0)
                                    <span class="text-amber-500 flex items-center gap-1"><span
                                            class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Sisa:
                                        {{ $m->stok }}</span>
                                @else
                                    <span class="text-red-500 flex items-center gap-1"><span
                                            class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Habis</span>
                                @endif
                            </div>

                            <button id="btn-checkout"
                                class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 shadow-sm transition-all flex items-center justify-center">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                            <div
                                class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-3">
                                <i class="fa-solid fa-utensils text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-700">Katalog Menu Kosong</p>
                            <p class="text-xs text-slate-400 mt-1">Menu tidak ditemukan atau seluruh persediaan stok produk
                                di dapur sedang habis.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="w-full lg:w-[380px] xl:w-[420px] shrink-0">
            <div
                class="bg-white rounded-2xl shadow-sm border border-slate-200 sticky top-6 flex flex-col h-[calc(100vh-3rem)] max-h-[780px] overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-shopping-cart text-blue-600"></i> Detail Orderan Kasir
                    </h2>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-3" id="cart-items">
                    <div
                        class="text-center text-slate-400 py-24 flex flex-col items-center justify-center max-w-[240px] mx-auto">
                        <div
                            class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 mb-3">
                            <i class="fa-solid fa-basket-shopping text-lg opacity-60"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-600">Keranjang Belanja Kosong</span>
                        <p class="text-[11px] text-slate-400 mt-1">Silakan klik item menu di sebelah kiri untuk merekam
                            daftar pesanan baru</p>
                    </div>
                </div>

                <div class="p-5 border-t border-slate-100 bg-slate-50/80 backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Tagihan</span>
                        <span id="cart-total-price" class="text-2xl font-black text-blue-600 tracking-tight">Rp 0</span>
                    </div>
                    <button
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-blue-500/10 transition-colors flex items-center justify-center gap-2 text-sm"
                        onclick="checkout()">
                        <i class="fa-solid fa-check-double text-base"></i> Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="custom-modal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div
            class="w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 text-center shadow-2xl transition-all scale-95 duration-300 border border-slate-100">
            <div id="modal-icon-container"
                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100/60">
                <i id="modal-icon" class="fa-solid fa-circle-info text-xl"></i>
            </div>
            <h3 id="modal-title" class="text-base font-bold text-slate-800 mb-1.5">Konfirmasi Sistem</h3>
            <p id="modal-message" class="text-xs text-slate-400 mb-6 leading-relaxed">
                Keterangan info sistem kasir...
            </p>
            <div id="modal-actions" class="flex justify-center gap-2.5 w-full">
                <button id="modal-btn-cancel"
                    class="hidden flex-1 px-4 py-3 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200/40 rounded-xl transition-colors">
                    Batal
                </button>
                <button id="modal-btn-ok"
                    class="flex-1 px-5 py-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/5 rounded-xl transition-colors">
                    Oke, Mengerti
                </button>
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
        let isProcessing = false;

        // --- ENGINE MODAL CUSTOM ---
        function showModal({
            title,
            message,
            type = 'info',
            showCancel = false,
            onConfirm = null
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
                    showModal({
                        title: 'Stok Terbatas',
                        message: 'Stok tidak mencukupi!',
                        type: 'error'
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
                    showModal({
                        title: 'Stok Habis',
                        message: 'Stok habis!',
                        type: 'error'
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
                showModal({
                    title: 'Stok Terbatas',
                    message: 'Stok tidak mencukupi!',
                    type: 'error'
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
                showModal({
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
            // cegah klik tombol saat proses
            if (isProcessing) return;

            const cartArray = Object.values(cart);
            if (cartArray.length === 0) {
                showModal({
                    title: 'Keranjang Kosong',
                    message: 'Pilih menu terlebih dahulu.',
                    type: 'info'
                });
                return;
            }

            if (blockCheckout) {
                return;
            }

            // Mengganti confirm() bawaan dengan modal konfirmasi kustom yang memiliki tombol Batal & Oke
            showModal({
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
            // Cegah eksekusi ganda jika tombol modal ditekan berkali-kali dengan cepat
            if (isProcessing) return;

            isProcessing = true; // Kunci sistem!

            // Tampilkan modal loading sesaat sebelum fetch dimulai
            showModal({
                title: 'Memproses Pembayaran...',
                message: 'Mohon tunggu sebentar, sistem sedang mencatat transaksi Anda.',
                type: 'info',
                showCancel: false
            });

            // Ubah teks tombol menjadi loading (opsional tapi disarankan)
            const btnOk = document.getElementById('modal-btn-ok');
            btnOk.disabled = true;
            btnOk.innerHTML = '<i class="fa-solid fa-check"></i> Selesai';

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
                    btnOk.disabled = false; // Kembalikan fungsi tombol
                    if (data.success) {
                        showModal({
                            title: 'Pembayaran Berhasil',
                            message: data.message,
                            type: 'success',
                            onConfirm: () => {
                                window.location.reload();
                            }
                        });
                    } else {
                        showModal({
                            title: 'Gagal',
                            message: 'Gagal: ' + data.message,
                            type: 'error'
                        });
                    }
                })
                .catch(err => {
                    btnOk.disabled = false;
                    showModal({
                        title: 'Error Sistem',
                        message: 'Terjadi kesalahan sistem saat memproses data.',
                        type: 'error'
                    });
                    console.error(err);
                });
        }
    </script>
@endpush
