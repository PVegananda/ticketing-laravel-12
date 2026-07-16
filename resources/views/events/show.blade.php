<x-app-layout>
    {{--
        HALAMAN DETAIL EVENT (PUBLIK)
        File: resources/views/events/show.blade.php
        Diakses di URL: /events/{id}

        Halaman ini menampilkan:
        1. Header event (gambar, judul, kategori, tanggal, lokasi, deskripsi)
        2. Daftar tiket yang tersedia
        3. Section "Event Terkait" (same category, upcoming, max 4)
    --}}

    <div class="max-w-7xl mx-auto py-8 px-6">

        {{-- ===== BAGIAN 1: HEADER EVENT ===== --}}
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <div class="flex flex-col lg:flex-row gap-8">

                    {{-- Kolom Kiri: Gambar Event --}}
                    <div class="lg:w-1/2">
                        <img src="{{ $event->image_url }}"
                             alt="{{ $event->judul ?? $event->nama }}"
                             class="w-full h-96 object-cover rounded-lg shadow-md">
                    </div>

                    {{-- Kolom Kanan: Detail Event --}}
                    <div class="lg:w-1/2">

                        {{-- Judul Event --}}
                        <h1 class="text-4xl font-bold mb-4">{{ $event->judul ?? $event->nama }}</h1>

                        {{-- Badge Kategori (hanya tampil jika ada kategori) --}}
                        @if ($event->kategori)
                            <div class="badge badge-primary badge-lg mb-4">
                                {{ $event->kategori->nama }}
                            </div>
                        @endif

                        {{-- Info Tanggal dan Lokasi --}}
                        <div class="space-y-4 mb-6">

                            {{-- Tanggal & Waktu --}}
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>
                                    {{--
                                        Cek field tanggal:
                                        - tanggal_waktu = field baru (datetime)
                                        - tanggal = field lama (untuk kompatibilitas)
                                        - translatedFormat dengan locale 'id' = format Indonesia
                                        Contoh output: "31 Desember 2026, 19:00"
                                    --}}
                                    @if ($event->tanggal_waktu)
                                        {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }}
                                    @elseif ($event->tanggal)
                                        {{ \Carbon\Carbon::parse($event->tanggal)->locale('id')->translatedFormat('d F Y, H:i') }}
                                    @else
                                        Tanggal tidak tersedia
                                    @endif
                                </span>
                            </div>

                            {{-- Lokasi --}}
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $event->lokasi ?? 'Lokasi tidak tersedia' }}</span>
                            </div>

                        </div>

                        {{-- Deskripsi Event (hanya tampil jika ada deskripsi) --}}
                        @if ($event->deskripsi)
                            <div class="prose max-w-none mb-6">
                                <h3 class="text-lg font-semibold mb-2">Deskripsi Event</h3>
                                <p class="text-gray-600">{{ $event->deskripsi }}</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- ===== BAGIAN 2: PILIHAN TIKET ===== --}}
        {{--
            Cek apakah event punya tiket:
            - $event->tikets = relasi hasMany ke model Tiket
            - count() > 0 = ada minimal 1 tiket
        --}}
        @if ($event->tikets && $event->tikets->count() > 0)
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-6">Pilih Tiket</h2>

                    {{-- Grid tiket: 1 kolom di mobile, 2 di tablet, 3 di desktop --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        {{-- Loop setiap tiket yang dimiliki event --}}
                        @foreach ($event->tikets as $tiket)
                            <div class="card bg-base-200 hover:bg-base-300 transition-colors duration-200">
                                <div class="card-body">

                                    {{-- Nama tipe tiket (ucfirst = huruf pertama kapital) --}}
                                    <h3 class="card-title text-lg">Tiket {{ ucfirst($tiket->tipe) }}</h3>

                                    {{-- Deskripsi tiket (opsional) --}}
                                    @if ($tiket->deskripsi)
                                        <p class="text-sm text-gray-600 mb-4">{{ $tiket->deskripsi }}</p>
                                    @endif

                                    <div class="flex justify-between items-center mb-4">

                                        {{-- Harga tiket dalam format Rupiah --}}
                                        <span class="text-2xl font-bold text-primary">
                                            Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                                        </span>

                                        {{-- Badge stok (hijau = tersedia, merah = habis) --}}
                                        @if ($tiket->stok !== null)
                                            <span class="badge {{ $tiket->stok > 0 ? 'badge-success' : 'badge-error' }}">
                                                {{ $tiket->stok > 0 ? $tiket->stok . ' tersedia' : 'Habis' }}
                                            </span>
                                        @endif

                                    </div>

                                    {{--
                                        Tombol beli:
                                        - Jika stok 0 atau null = disabled (tidak bisa diklik)
                                        - Jika stok > 0 = aktif dan mengarah ke form checkout
                                    --}}
                                    @if ($tiket->stok !== null && $tiket->stok <= 0)
                                        <button class="btn btn-primary w-full btn-disabled" disabled>
                                            Habis Terjual
                                        </button>
                                    @else
                                        @auth
                                            @if(Auth::user()->role === 'user')
                                                <a href="{{ route('checkout.index', ['tiket_id' => $tiket->id]) }}" class="btn btn-primary w-full">
                                                    Beli Sekarang
                                                </a>
                                            @else
                                                <button class="btn btn-primary w-full btn-disabled" disabled title="Admin tidak dapat membeli tiket">
                                                    Beli Sekarang
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary w-full">
                                                Login untuk Beli
                                            </a>
                                        @endauth
                                    @endif

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

        @else
            {{-- Tampilkan pesan jika belum ada tiket --}}
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body text-center">
                    <h3 class="text-xl font-semibold mb-2">Tiket Tidak Tersedia</h3>
                    <p class="text-gray-600">Belum ada tiket yang tersedia untuk event ini.</p>
                </div>
            </div>
        @endif

        {{-- ===== BAGIAN 3: EVENT TERKAIT ===== --}}
        {{--
            Section ini menampilkan event lain yang:
            1. Kategorinya sama dengan event yang sedang ditampilkan
            2. Tanggalnya masih akan datang (upcoming)
            3. Maksimal 4 event

            $relatedEvents dikirim dari EventController@show
            Menggunakan component <x-event-card> yang ada di:
            resources/views/components/event-card.blade.php
        --}}
        @if (isset($relatedEvents) && $relatedEvents->count() > 0)
            <div class="mt-10">
                <h2 class="text-2xl font-bold mb-6">Event Terkait</h2>

                {{-- Grid 4 kolom (1 di mobile, 2 di tablet, 4 di desktop) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    {{-- Loop setiap event terkait dan tampilkan pakai component --}}
                    @foreach ($relatedEvents as $related)
                        <x-event-card
                            :title="$related->judul ?? $related->nama"
                            :date="$related->tanggal_waktu ?? $related->tanggal"
                            :location="$related->lokasi ?? 'Lokasi tidak tersedia'"
                            :price="$related->tikets->min('harga')"
                            :image="$related->gambar"
                            :href="route('events.show', $related)"
                        />
                    @endforeach

                </div>
            </div>
        @endif

        {{-- Tombol kembali ke halaman utama --}}
        <div class="mt-8">
            <a href="{{ route('home') }}" class="btn btn-outline btn-wide">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

    </div>
</x-app-layout>