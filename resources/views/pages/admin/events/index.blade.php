@extends('layouts.admin_layouts')

@section('title', 'Manajemen Event')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-2xl font-bold">
                Manajemen Event
            </h2>

            <p class="text-gray-500 mt-1">
                Kelola seluruh data event yang tersedia.
            </p>
        </div>

        <a href="{{ route('admin.events.create') }}"
            class="btn btn-primary">
            + Tambah Event
        </a>

    </div>

    {{-- Error Alert --}}
    @if ($errors->any())

        <div class="alert alert-error">

            <ul class="list-disc ml-5">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- Filter Card --}}
    <div class="card bg-base-100 shadow">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.events.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Search --}}
                <div>

                    <label class="label">

                        <span class="label-text">
                            Cari Event
                        </span>

                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul atau lokasi..."
                        class="input input-bordered w-full">

                </div>

                {{-- Kategori --}}
                <div>

                    <label class="label">

                        <span class="label-text">
                            Kategori
                        </span>

                    </label>

                    <select
                        name="kategori_id"
                        class="select select-bordered w-full">

                        <option value="">
                            Semua Kategori
                        </option>

                        @foreach ($kategoris as $kategori)

                            <option
                                value="{{ $kategori->id }}"
                                @selected(request('kategori_id') == $kategori->id)>

                                {{ $kategori->nama }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Sort --}}
                <div>

                    <label class="label">

                        <span class="label-text">
                            Urutkan
                        </span>

                    </label>

                    <select
                        name="sort"
                        class="select select-bordered w-full">

                        <option
                            value="asc"
                            @selected(request('sort') == 'asc')>

                            Terlama

                        </option>

                        <option
                            value="desc"
                            @selected(request('sort') == 'desc')>

                            Terbaru

                        </option>

                    </select>

                </div>

                {{-- Action Button --}}
                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary flex-1">

                        Filter

                    </button>

                    <a
                        href="{{ route('admin.events.index') }}"
                        class="btn btn-outline">

                        Reset

                    </a>

                </div>

            </form>

        </div>

    </div>

    {{-- Table Card --}}
    <div class="card bg-base-100 shadow">

        <div class="card-body p-0">

            <div class="overflow-x-auto">

                <table class="table table-zebra">

                    <thead>

                        <tr>

                            <th>Gambar</th>

                            <th>Judul</th>

                            <th>Kategori</th>

                            <th>Tanggal</th>

                            <th>Lokasi</th>

                            <th>Status</th>

                            <th class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>
                        @forelse ($events as $event)
                        <tr>

                            {{-- Gambar --}}
                            <td>
                                <img
                                    src="{{ $event->image_url }}"
                                    alt="{{ $event->judul }}"
                                    class="w-16 h-16 rounded object-cover">
                            </td>

                            {{-- Judul --}}
                            <td>

                                <div class="font-semibold">

                                    {{ $event->judul }}

                                </div>

                                <div class="text-xs text-gray-500">

                                    {{ $event->tikets->count() }} Jenis Tiket

                                </div>

                            </td>

                            {{-- Kategori --}}
                            <td>

                                {{ $event->kategori->nama }}

                            </td>

                            {{-- Tanggal --}}
                            <td>

                                {{ $event->tanggal_waktu->format('d M Y') }}

                                <br>

                                <span class="text-xs text-gray-500">

                                    {{ $event->tanggal_waktu->format('H:i') }}

                                </span>

                            </td>

                            {{-- Lokasi --}}
                            <td>

                                {{ $event->lokasi }}

                            </td>

                            {{-- Status --}}
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

                            {{-- Action --}}
                            <td>

                                <div class="flex justify-center gap-2">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="btn btn-sm btn-info">

                                        View

                                    </a>

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.events.edit', $event) }}"
                                        class="btn btn-sm btn-warning">

                                        Edit

                                    </a>

                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.events.destroy', $event) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus event ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-error">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

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

    {{-- Pagination --}}
    <div>

        {{ $events->appends(request()->except('page'))->links() }}

    </div>

</div>

@endsection