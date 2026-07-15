{{--
    HALAMAN TAMBAH EVENT BARU - ADMIN
    File: resources/views/pages/admin/events/create.blade.php
    Diakses di URL: /admin/events/create

    Form ini memiliki:
    1. Field data event (judul, kategori, lokasi, tanggal, gambar, deskripsi)
    2. Section tiket dinamis (bisa tambah/hapus tiket dengan JavaScript)
    3. Preview gambar sebelum submit
--}}

@extends('layouts.admin_layouts')

@section('title', 'Tambah Event')

@section('content')

<div class="space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-2xl font-bold">
                Tambah Event
            </h2>

            <p class="text-gray-500">
                Tambahkan event baru beserta tiketnya.
            </p>

        </div>

        {{-- Tombol kembali ke daftar event --}}
        <a
            href="{{ route('admin.events.index') }}"
            class="btn btn-outline">
            ← Kembali
        </a>

    </div>

    {{-- ===== FORM CARD ===== --}}
    <div class="card bg-base-100 shadow">

        <div class="card-body">

            {{--
                Form action: route store = POST /admin/events
                enctype="multipart/form-data" wajib ada jika form punya input file (gambar)!
                Tanpa ini, file gambar tidak akan terkirim ke server.
            --}}
            {{-- Menampilkan error validasi umum jika ada --}}
            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('admin.events.store') }}"
                method="POST"
                enctype="multipart/form-data">

                {{-- @csrf: Token keamanan Laravel untuk mencegah CSRF Attack --}}
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- ===== JUDUL EVENT ===== --}}
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Judul Event</span>
                            <span class="text-error">*</span> {{-- Tanda bintang = wajib diisi --}}
                        </label>

                        {{--
                            value="{{ old('judul') }}" = mengisi ulang nilai input jika validasi gagal.
                            old() mengambil nilai yang sebelumnya dikirim user agar tidak perlu isi ulang.
                        --}}
                        <input
                            type="text"
                            name="judul"
                            value="{{ old('judul') }}"
                            class="input input-bordered w-full">

                        {{-- Tampilkan pesan error jika validasi field 'judul' gagal --}}
                        @error('judul')
                            <span class="text-error text-sm">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- ===== KATEGORI ===== --}}
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Kategori</span>
                            <span class="text-error">*</span>
                        </label>

                        {{-- Dropdown kategori, diisi dari $kategoris yang dikirim controller --}}
                        <select
                            name="kategori_id"
                            class="select select-bordered w-full">

                            <option value="">
                                Pilih Kategori
                            </option>

                            {{-- Loop semua kategori yang ada --}}
                            @foreach($kategoris as $kategori)

                                <option
                                    value="{{ $kategori->id }}"
                                    {{-- Tetap terselect jika validasi gagal --}}
                                    @selected(old('kategori_id') == $kategori->id)>

                                    {{ $kategori->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- ===== LOKASI ===== --}}
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Lokasi</span>
                            <span class="text-error">*</span>
                        </label>

                        <input
                            type="text"
                            name="lokasi"
                            value="{{ old('lokasi') }}"
                            class="input input-bordered w-full">

                    </div>

                    {{-- ===== TANGGAL & WAKTU ===== --}}
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Tanggal & Waktu</span>
                            <span class="text-error">*</span>
                        </label>

                        {{-- datetime-local = input tanggal + waktu dalam satu field --}}
                        <input
                            type="datetime-local"
                            name="tanggal_waktu"
                            value="{{ old('tanggal_waktu') }}"
                            class="input input-bordered w-full">

                    </div>

                    {{-- ===== UPLOAD GAMBAR ===== --}}
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Gambar Event</span>
                            {{-- Tidak ada bintang = opsional, bisa dikosongkan --}}
                        </label>

                        {{--
                            accept=".jpg,.jpeg,.png" = hanya menerima file JPG dan PNG
                            id="gambar" digunakan oleh JavaScript untuk preview gambar
                        --}}
                        <input
                            type="file"
                            name="gambar"
                            id="gambar"
                            accept=".jpg,.jpeg,.png"
                            class="file-input file-input-bordered w-full">

                        <p class="text-xs text-gray-500">
                            Maksimal ukuran gambar 2 MB (JPG, JPEG, PNG)
                        </p>

                        {{-- Tampilkan error jika file terlalu besar atau format salah --}}
                        @error('gambar')
                            <span class="text-error text-sm">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- ===== PREVIEW GAMBAR ===== --}}
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Preview</span>
                        </label>

                        {{--
                            id="preview-image" digunakan JavaScript untuk menampilkan preview.
                            class="hidden" = awalnya tersembunyi, akan ditampilkan saat user pilih file.
                        --}}
                        <img
                            id="preview-image"
                            class="hidden w-full h-56 rounded-lg border object-cover">

                    </div>

                    {{-- ===== DESKRIPSI ===== --}}
                    {{-- md:col-span-2 = mengambil 2 kolom penuh di layar medium ke atas --}}
                    <div class="space-y-2 md:col-span-2">

                        <label class="block">
                            <span class="text-sm font-medium">Deskripsi</span>
                            <span class="text-error">*</span>
                        </label>

                        {{-- Textarea untuk deskripsi panjang event --}}
                        <textarea
                            name="deskripsi"
                            rows="5"
                            class="textarea textarea-bordered w-full">{{ old('deskripsi') }}</textarea>

                        @error('deskripsi')
                            <span class="text-error text-sm">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

                {{-- ===== SECTION TIKET DINAMIS ===== --}}
                <div class="divider">
                    Tiket Event
                </div>

                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-lg font-semibold">
                        Daftar Tiket
                    </h3>

                    {{--
                        Tombol tambah tiket - dikontrol oleh JavaScript di bawah
                        type="button" penting! Kalau type="submit" akan submit form duluan
                    --}}
                    <button
                        type="button"
                        id="add-ticket"
                        class="btn btn-primary btn-sm">
                        + Tambah Tiket
                    </button>

                </div>

                {{--
                    Container kosong yang akan diisi tiket oleh JavaScript.
                    JavaScript akan membuat card tiket baru dan dimasukkan ke sini.
                --}}
                <div
                    id="ticket-container"
                    class="space-y-4">
                </div>

                {{-- Tombol submit form --}}
                <div class="mt-6 flex justify-end">

                    <button
                        type="submit"
                        class="btn btn-success">
                        Simpan Event
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ===== JAVASCRIPT: TIKET DINAMIS + PREVIEW GAMBAR ===== --}}
<script>

// Tunggu sampai seluruh DOM selesai dimuat sebelum menjalankan script
document.addEventListener('DOMContentLoaded', function () {

    // Ambil elemen-elemen yang diperlukan
    const container    = document.getElementById('ticket-container');  // wadah tiket
    const addButton    = document.getElementById('add-ticket');         // tombol tambah
    const imageInput   = document.getElementById('gambar');             // input file gambar
    const previewImage = document.getElementById('preview-image');      // img preview

    // Counter untuk penomoran tiket dan penamaan field (tikets[0], tikets[1], dst.)
    let ticketIndex = 0;

    // ===== FITUR PREVIEW GAMBAR =====
    // Saat user memilih file gambar, tampilkan preview-nya secara langsung
    imageInput.addEventListener('change', function (e) {

        const file = e.target.files[0]; // ambil file pertama yang dipilih

        // Jika tidak ada file dipilih, sembunyikan preview
        if (!file) {
            previewImage.classList.add('hidden');
            return;
        }

        // createObjectURL = buat URL sementara untuk menampilkan file lokal
        previewImage.src = URL.createObjectURL(file);
        previewImage.classList.remove('hidden'); // tampilkan img

    });

    // ===== FUNGSI RENDER CARD TIKET =====
    // Membuat HTML card tiket baru dan menambahkannya ke container
    function renderTicket(tipe = 'reguler', harga = '', stok = '') {

        const card = document.createElement('div');
        card.className = 'card bg-base-200 border';

        // Template literal (backtick) untuk membuat HTML card tiket
        // ticketIndex digunakan untuk penamaan field array, contoh: tikets[0][tipe]
        card.innerHTML = `
            <div class="card-body">

                <div class="flex justify-between items-center">

                    <h4 class="font-semibold">
                        Tiket #${ticketIndex + 1}
                    </h4>

                    <!-- Tombol hapus tiket ini dari daftar -->
                    <button
                        type="button"
                        class="btn btn-error btn-sm remove-ticket">
                        Hapus
                    </button>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Tipe Tiket -->
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Tipe Tiket</span>
                            <span class="text-error">*</span>
                        </label>

                        <!-- name="tikets[0][tipe]" → akan diterima controller sebagai array -->
                        <select
                            name="tikets[${ticketIndex}][tipe]"
                            class="select select-bordered w-full">

                            <option value="reguler" ${tipe === 'reguler' ? 'selected' : ''}>Reguler</option>
                            <option value="premium" ${tipe === 'premium' ? 'selected' : ''}>Premium</option>

                        </select>

                    </div>

                    <!-- Harga Tiket -->
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Harga</span>
                            <span class="text-error">*</span>
                        </label>

                        <input
                            type="number"
                            min="0"
                            name="tikets[${ticketIndex}][harga]"
                            value="${harga}"
                            class="input input-bordered w-full">

                    </div>

                    <!-- Stok Tiket -->
                    <div class="space-y-2">

                        <label class="block">
                            <span class="text-sm font-medium">Stok</span>
                            <span class="text-error">*</span>
                        </label>

                        <input
                            type="number"
                            min="0"
                            name="tikets[${ticketIndex}][stok]"
                            value="${stok}"
                            class="input input-bordered w-full">

                    </div>

                </div>

            </div>
        `;

        // Tambahkan card tiket ke container
        container.appendChild(card);

        // Naikkan index untuk tiket berikutnya
        ticketIndex++;

    }

    // ===== EVENT: KLIK TOMBOL TAMBAH TIKET =====
    addButton.addEventListener('click', function () {
        renderTicket(); // panggil fungsi untuk membuat card tiket baru
    });

    // ===== EVENT: KLIK TOMBOL HAPUS TIKET =====
    // Menggunakan event delegation pada container (lebih efisien dari attach ke setiap tombol)
    container.addEventListener('click', function (e) {

        // Cek apakah yang diklik adalah tombol dengan class 'remove-ticket'
        if (e.target.classList.contains('remove-ticket')) {

            // .closest('.card') = cari elemen ancestor terdekat dengan class 'card'
            // lalu hapus elemen tersebut (card tiket) dari DOM
            e.target.closest('.card').remove();

        }

    });

    // ===== INISIALISASI =====
    // Tampilkan 1 tiket langsung saat halaman dimuat atau isi dari data lama jika ada error validasi
    const oldTikets = @json(old('tikets', []));
    
    if (oldTikets.length > 0) {
        // Render tiket dari input sebelumnya yang gagal divalidasi
        oldTikets.forEach(tiket => {
            renderTicket(tiket.tipe, tiket.harga, tiket.stok);
        });
    } else {
        // Tampilkan 1 tiket kosong secara default
        renderTicket();
    }

});

</script>

@endsection