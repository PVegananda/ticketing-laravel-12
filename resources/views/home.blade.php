<x-app-layout>
    <div class="hero bg-blue-900 min-h-screen">
        <div class="hero-content text-center text-white">
            <div class="max-w-4xl w-full">
                <h1 class="text-5xl font-bold">Hi, Amankan Tiketmu yuk.</h1>
                <p class="py-6">
                    BengTix: Beli tiket, auto asik.
                </p>
                
                {{-- Form Pencarian Event --}}
                <form action="{{ route('home') }}" method="GET" class="flex gap-2 justify-center max-w-lg mx-auto">
                    {{-- Pertahankan filter kategori jika sedang aktif --}}
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event konser, pameran..." class="input input-bordered w-full text-black" />
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </div>
    </div>

    <section class="max-w-7xl mx-auto py-12 px-6">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black uppercase italic">Event</h2>
            <div class="flex gap-2">
                <a href="{{ route('home', ['search' => request('search')]) }}">
                    <x-ui.category-pill :label="'Semua'" :active="!request('kategori')" />
                </a>
                @foreach($categories as $kategori)
                    <a href="{{ route('home', ['kategori' => $kategori->id, 'search' => request('search')]) }}">
                        <x-ui.category-pill :label="$kategori->nama" :active="request('kategori') == $kategori->id" />
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($events as $event)
                <x-event-card :title="$event->judul" :date="$event->tanggal_waktu" :location="$event->lokasi"
                    :price="$event->tikets_min_harga" :image="$event->gambar" :href="route('events.show', $event)" />
            @endforeach
        </div>
    </section>
</x-app-layout>