{{--
    HALAMAN DAFTAR EVENT - ADMIN
    File: resources/views/pages/admin/events/index.blade.php
    Diakses di URL: /admin/events

    Fitur halaman ini:
    1. Header dengan tombol "Tambah Event"
    2. Form filter (search, kategori, sort)
    3. Tabel daftar event dengan pagination
    4. Tombol aksi: View, Edit, Delete
--}}

@extends('layouts.admin_layouts')

@section('title', 'Manajemen Event')

@section('content')

<div class="space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-2xl font-bold">
                Manajemen Event
            </h2>

            <p class="text-gray-500 mt-1">
                Kelola seluruh data event yang tersedia.
            </p>
        </div>

        {{-- Tombol navigasi ke halaman form tambah event baru --}}
        <a href="{{ route('admin.events.create') }}"
            class="btn btn-primary">
            + Tambah Event
        </a>

    </div>

    {{-- ===== ERROR ALERT ===== --}}
    {{--
        Menampilkan pesan error jika ada validasi yang gagal
        atau ada error dari controller (misal: gagal hapus event yang ada penjualan)
        session('error') = pesan dari ->with('error', '...')
    --}}
    @if ($errors->any())

        <div class="alert alert-error">

            <ul class="list-disc ml-5">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- Tampilkan pesan error dari session (misal: gagal hapus event) --}}
    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===== FORM FILTER ===== --}}
    {{--
        Filter menggunakan method GET supaya URL berubah saat filter digunakan.
        Ini memudahkan bookmark/share URL dengan filter tertentu.
        Contoh URL setelah filter: /admin/events?search=konser&kategori_id=1&sort=desc
    --}}
    <div class="card bg-base-100 shadow">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.events.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Input pencarian (search by judul atau lokasi) --}}
                <div>

                    <label class="label">

                        <span class="label-text">
                            Cari Event
                        </span>

                    </label>

                    {{--
                        request('search') = ambil nilai search dari URL query string
                        supaya nilai tetap terisi saat halaman di-reload setelah filter
                    --}}
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul atau lokasi..."
                        class="input input-bordered w-full">

                </div>

                {{-- Dropdown filter kategori --}}
                <div>

                    <label class="label">

                        <span class="label-text">
                            Kategori
                        </span>

                    </label>

                    <select
                        name="kategori_id"
                        class="select select-bordered w-full">

                        {{-- Pilihan default: semua kategori (tidak filter) --}}
                        <option value="">
                            Semua Kategori
                        </option>

                        {{-- Loop semua kategori dari database, $kategoris dikirim dari controller --}}
                        @foreach ($kategoris as $kategori)

                            <option
                                value="{{ $kategori->id }}"
                                {{-- @selected = tambahkan attribute 'selected' jika kondisi true --}}
                                @selected(request('kategori_id') == $kategori->id)>

                                {{ $kategori->nama }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Dropdown sort (urutan tanggal) --}}
                <div>

                    <label class="label">

                        <span class="label-text">
                            Urutkan
                        </span>

                    </label>

                    <select
                        name="sort"
                        class="select select-bordered w-full">

                        {{-- asc = ascending = terlama dulu (default) --}}
                        <option
                            value="asc"
                            @selected(request('sort') == 'asc')>
                            Terlama
                        </option>

                        {{-- desc = descending = terbaru dulu --}}
                        <option
                            value="desc"
                            @selected(request('sort') == 'desc')>
                            Terbaru
                        </option>

                    </select>

                </div>

                {{-- Tombol filter dan reset --}}
                <div class="flex items-end gap-2">

                    {{-- Submit form untuk menerapkan filter --}}
                    <button
                        type="submit"
                        class="btn btn-primary flex-1">
                        Filter
                    </button>

                    {{-- Link reset: kembali ke URL tanpa filter sama sekali --}}
                    <a
                        href="{{ route('admin.events.index') }}"
                        class="btn btn-outline">
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>

    {{-- ===== TABEL DATA EVENT ===== --}}
    <div class="card bg-base-100 shadow">

        <div class="card-body p-0">

            <div class="overflow-x-auto">

                {{-- table-zebra = baris bergantian warna untuk mudah dibaca --}}
                <table class="table table-zebra">

                    <thead>

                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        {{--
                            @forelse = seperti @foreach tapi ada @empty untuk kondisi data kosong
                            $events adalah hasil paginate() dari controller,
                            bukan collection biasa, sehingga bisa pakai ->links() untuk pagination
                        --}}
                        @forelse ($events as $event)
                        <tr>

                            {{-- Gambar thumbnail event --}}
                            <td>
                                {{--
                                    image_url adalah accessor di model Event
                                    yang secara otomatis mengembalikan URL gambar yang benar
                                --}}
                                <img
                                    src="{{ $event->image_url }}"
                                    alt="{{ $event->judul }}"
                                    class="w-16 h-16 rounded object-cover">
                            </td>

                            {{-- Judul dan jumlah jenis tiket --}}
                            <td>

                                <div class="font-semibold">
                                    {{ $event->judul }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $event->tikets->count() }} Jenis Tiket
                                </div>

                            </td>

                            {{-- Nama kategori event --}}
                            <td>
                                {{ $event->kategori->nama }}
                            </td>

                            {{-- Tanggal dan waktu event --}}
                            <td>
                                {{-- format('d M Y') = contoh: 31 Des 2026 --}}
                                {{ $event->tanggal_waktu->format('d M Y') }}
                                <br>
                                {{-- format('H:i') = contoh: 19:00 --}}
                                <span class="text-xs text-gray-500">
                                    {{ $event->tanggal_waktu->format('H:i') }}
                                </span>
                            </td>

                            {{-- Lokasi event --}}
                            <td>
                                {{ $event->lokasi }}
                            </td>

                            {{--
                                Status event (dihitung otomatis dari accessor model):
                                - Upcoming = tanggal > sekarang
                                - Ongoing  = sedang berlangsung
                                - Completed = sudah selesai
                            --}}
                            <td>

                                @if ($event->status == 'Upcoming')

                                    <span class="badge badge-info">
                                        Upcoming
                                    </span>

                                @elseif ($event->status == 'Ongoing')

                                    <span class="badge badge-success">
                                        Ongoing
                                    </span>

                                @else

                                    <span class="badge badge-error">
                                        Completed
                                    </span>

                                @endif

                            </td>

                            {{-- Tombol aksi: View, Edit, Delete --}}
                            <td>

                                <div class="flex justify-center gap-2">

                                    {{-- View: ke halaman detail event publik --}}
                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="btn btn-sm btn-info">
                                        View
                                    </a>

                                    {{-- Edit: ke form edit event --}}
                                    <a
                                        href="{{ route('admin.events.edit', $event) }}"
                                        class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    {{--
                                        Delete: menggunakan form POST dengan method spoofing DELETE
                                        Karena HTML form hanya support GET dan POST,
                                        Laravel menggunakan @method('DELETE') untuk spoofing
                                        onsubmit = konfirmasi sebelum hapus
                                    --}}
                                    <form
                                        action="{{ route('admin.events.destroy', $event) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus event ini?')">

                                        @csrf          {{-- Token keamanan untuk mencegah CSRF attack --}}
                                        @method('DELETE') {{-- Method spoofing untuk HTTP DELETE --}}

                                        <button
                                            class="btn btn-sm btn-error">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        {{-- Tampilkan pesan jika tidak ada data event --}}
                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-10 text-gray-500">

                                Belum ada data event.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- ===== PAGINATION ===== --}}
    {{--
        appends(request()->except('page')) = supaya parameter filter (search, kategori_id, sort)
        tetap terbawa saat pindah halaman pagination.
        Tanpa ini, saat pindah halaman, filter akan hilang.
    --}}
    <div>

        {{ $events->appends(request()->except('page'))->links() }}

    </div>

</div>

@endsection