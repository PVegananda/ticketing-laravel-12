@extends('layouts.admin_layouts')

@section('title', 'Edit Event')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-2xl font-bold">

                Edit Event

            </h2>

            <p class="text-gray-500">

                Perbarui informasi event.

            </p>

        </div>

        <a
            href="{{ route('admin.events.index') }}"
            class="btn btn-outline">

            ← Kembali

        </a>

    </div>

    {{-- Warning --}}
    @if($hasSales)

        <div class="alert alert-warning">

            <span>

                ⚠ Event ini sudah memiliki penjualan tiket.
                Beberapa data tidak dapat diubah.

            </span>

        </div>

    @endif

    {{-- Form --}}
    <div class="card bg-base-100 shadow">

        <div class="card-body">

            <form
                action="{{ route('admin.events.update', $event) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Judul --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Judul Event

                            </span>

                            <span class="text-error">*</span>

                        </label>

                        <input
                            type="text"
                            name="judul"
                            value="{{ old('judul', $event->judul) }}"
                            class="input input-bordered w-full">

                    </div>

                    {{-- Kategori --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Kategori

                            </span>

                        </label>

                        <select
                            name="kategori_id"
                            class="select select-bordered w-full">

                            @foreach($kategoris as $kategori)

                                <option
                                    value="{{ $kategori->id }}"
                                    @selected(old('kategori_id', $event->kategori_id) == $kategori->id)>

                                    {{ $kategori->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Lokasi --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Lokasi

                            </span>

                        </label>

                        <input
                            type="text"
                            name="lokasi"
                            value="{{ old('lokasi', $event->lokasi) }}"
                            class="input input-bordered w-full">

                    </div>

                    {{-- Tanggal --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Tanggal & Waktu

                            </span>

                            @if($hasSales)

                                <span class="text-warning">

                                    (Tidak dapat diubah)

                                </span>

                            @endif

                        </label>

                        <input
                            type="datetime-local"
                            name="tanggal_waktu"
                            value="{{ old('tanggal_waktu', $event->tanggal_waktu->format('Y-m-d\TH:i')) }}"
                            class="input input-bordered w-full"
                            @if($hasSales) readonly @endif>

                    </div>
                                        {{-- Gambar --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Gambar Event

                            </span>

                        </label>

                        <input
                            type="file"
                            name="gambar"
                            id="gambar"
                            accept=".jpg,.jpeg,.png"
                            class="file-input file-input-bordered w-full">

                        <p class="text-xs text-gray-500">

                            Kosongkan jika tidak ingin mengganti gambar.

                        </p>

                    </div>

                    {{-- Preview Gambar --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Gambar Saat Ini

                            </span>

                        </label>

                        <img
                            id="preview-image"
                            src="{{ $event->image_url }}"
                            class="w-full h-56 rounded-lg object-cover border">

                    </div>

                    {{-- Deskripsi --}}
                    <div class="space-y-2 md:col-span-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Deskripsi

                            </span>

                        </label>

                        <textarea
                            name="deskripsi"
                            rows="5"
                            class="textarea textarea-bordered w-full">{{ old('deskripsi', $event->deskripsi) }}</textarea>

                    </div>

                </div>

                {{-- Ticket Section --}}
                <div class="divider">

                    Tiket Event

                </div>

                @unless($hasSales)

                    <div class="flex justify-between items-center mb-5">

                        <h3 class="text-lg font-semibold">

                            Daftar Tiket

                        </h3>

                        <button
                            type="button"
                            id="add-ticket"
                            class="btn btn-primary btn-sm">

                            + Tambah Tiket

                        </button>

                    </div>

                @else

                    <div class="alert alert-info mb-4">

                        Tiket tidak dapat ditambah maupun dihapus karena event sudah memiliki penjualan.

                    </div>

                @endunless

                <div
                    id="ticket-container"
                    class="space-y-4">

                    @foreach($event->tikets as $index => $tiket)

                        <div class="card bg-base-200 border">

                            <div class="card-body">

                                <input
                                    type="hidden"
                                    name="tikets[{{ $index }}][id]"
                                    value="{{ $tiket->id }}">

                                <div class="flex justify-between items-center">

                                    <h4 class="font-semibold">

                                        Tiket #{{ $loop->iteration }}

                                    </h4>

                                    @unless($hasSales)

                                        <button
                                            type="button"
                                            class="btn btn-error btn-sm remove-ticket">

                                            Hapus

                                        </button>

                                    @endunless

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                    {{-- Tipe --}}
                                    <div>

                                        <label class="block text-sm mb-2">

                                            Tipe

                                        </label>

                                        <select
                                            name="tikets[{{ $index }}][tipe]"
                                            class="select select-bordered w-full">

                                            <option
                                                value="reguler"
                                                @selected($tiket->tipe == 'reguler')>

                                                Reguler

                                            </option>

                                            <option
                                                value="premium"
                                                @selected($tiket->tipe == 'premium')>

                                                Premium

                                            </option>

                                        </select>

                                    </div>

                                    {{-- Harga --}}
                                    <div>

                                        <label class="block text-sm mb-2">

                                            Harga

                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="tikets[{{ $index }}][harga]"
                                            value="{{ $tiket->harga }}"
                                            class="input input-bordered w-full">

                                    </div>

                                    {{-- Stok --}}
                                    <div>

                                        <label class="block text-sm mb-2">

                                            Stok

                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="tikets[{{ $index }}][stok]"
                                            value="{{ $tiket->stok }}"
                                            class="input input-bordered w-full">

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="mt-8 flex justify-end gap-3">

                    <a
                        href="{{ route('admin.events.index') }}"
                        class="btn btn-outline">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Update Event

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('ticket-container');
    const imageInput = document.getElementById('gambar');
    const previewImage = document.getElementById('preview-image');

    @unless($hasSales)

    const addButton = document.getElementById('add-ticket');

    let ticketIndex = {{ $event->tikets->count() }};

    // Preview gambar
    imageInput.addEventListener('change', function (e) {

        const file = e.target.files[0];

        if (!file) return;

        previewImage.src = URL.createObjectURL(file);

    });

    // Tambah tiket baru
    function addTicket() {

        const card = document.createElement('div');

        card.className = 'card bg-base-200 border';

        card.innerHTML = `

            <div class="card-body">

                <div class="flex justify-between items-center">

                    <h4 class="font-semibold">

                        Tiket Baru

                    </h4>

                    <button
                        type="button"
                        class="btn btn-error btn-sm remove-ticket">

                        Hapus

                    </button>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>

                        <label class="block text-sm mb-2">

                            Tipe

                        </label>

                        <select
                            name="tikets[${ticketIndex}][tipe]"
                            class="select select-bordered w-full">

                            <option value="reguler">

                                Reguler

                            </option>

                            <option value="premium">

                                Premium

                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="block text-sm mb-2">

                            Harga

                        </label>

                        <input
                            type="number"
                            min="0"
                            name="tikets[${ticketIndex}][harga]"
                            class="input input-bordered w-full">

                    </div>

                    <div>

                        <label class="block text-sm mb-2">

                            Stok

                        </label>

                        <input
                            type="number"
                            min="0"
                            name="tikets[${ticketIndex}][stok]"
                            class="input input-bordered w-full">

                    </div>

                </div>

            </div>

        `;

        container.appendChild(card);

        ticketIndex++;

    }

    addButton.addEventListener('click', function () {

        addTicket();

    });

    container.addEventListener('click', function (e) {

        if (e.target.classList.contains('remove-ticket')) {

            e.target.closest('.card').remove();

        }

    });

    @else

    // Jika event sudah memiliki penjualan,
    // hanya aktifkan preview gambar.

    imageInput.addEventListener('change', function (e) {

        const file = e.target.files[0];

        if (!file) return;

        previewImage.src = URL.createObjectURL(file);

    });

    @endunless

});

</script>

@endsection
                