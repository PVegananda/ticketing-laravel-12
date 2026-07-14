@extends('layouts.admin_layouts')

@section('title', 'Tambah Event')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-2xl font-bold">

                Tambah Event

            </h2>

            <p class="text-gray-500">

                Tambahkan event baru beserta tiketnya.

            </p>

        </div>

        <a
            href="{{ route('admin.events.index') }}"
            class="btn btn-outline">

            ← Kembali

        </a>

    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow">

        <div class="card-body">

            <form
                action="{{ route('admin.events.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

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
                            value="{{ old('judul') }}"
                            class="input input-bordered w-full">

                        @error('judul')

                            <span class="text-error text-sm">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>

                    {{-- Kategori --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Kategori

                            </span>

                            <span class="text-error">*</span>

                        </label>

                        <select
                            name="kategori_id"
                            class="select select-bordered w-full">

                            <option value="">

                                Pilih Kategori

                            </option>

                            @foreach($kategoris as $kategori)

                                <option
                                    value="{{ $kategori->id }}"
                                    @selected(old('kategori_id') == $kategori->id)>

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

                            <span class="text-error">*</span>

                        </label>

                        <input
                            type="text"
                            name="lokasi"
                            value="{{ old('lokasi') }}"
                            class="input input-bordered w-full">

                    </div>

                    {{-- Tanggal --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">

                                Tanggal & Waktu

                            </span>

                            <span class="text-error">*</span>

                        </label>

                        <input
                            type="datetime-local"
                            name="tanggal_waktu"
                            value="{{ old('tanggal_waktu') }}"
                            class="input input-bordered w-full">

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
                            Maksimal ukuran gambar 2 MB (JPG, JPEG, PNG)
                        </p>

                        @error('gambar')
                            <span class="text-error text-sm">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- Preview Gambar --}}
                    <div class="space-y-2">

                        <label class="block">

                            <span class="text-sm font-medium">
                                Preview
                            </span>

                        </label>

                        <img
                            id="preview-image"
                            class="hidden w-full h-56 rounded-lg border object-cover">

                    </div>

                    {{-- Deskripsi --}}
                    <div class="space-y-2 md:col-span-2">

                        <label class="block">

                            <span class="text-sm font-medium">
                                Deskripsi
                            </span>

                            <span class="text-error">*</span>

                        </label>

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

                {{-- Dynamic Ticket --}}
                <div class="divider">
                    Tiket Event
                </div>

                <div class="flex justify-between items-center mb-4">

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

                <div
                    id="ticket-container"
                    class="space-y-4">

                </div>

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