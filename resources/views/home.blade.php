<x-app-layout>
    <div class="hero min-h-screen" style="background-image: url('https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=2070&auto=format&fit=crop');">
        <div class="hero-overlay bg-opacity-70 bg-gray-900"></div>
        <div class="hero-content text-center text-white z-10">
            <div class="max-w-4xl w-full">
                <h1 class="text-5xl font-bold drop-shadow-lg">Hi, Amankan Tiketmu yuk.</h1>
                <p class="py-6 text-lg font-medium drop-shadow-md">
                    BengTix: Beli tiket konser, pameran, dan festival favoritmu dengan mudah dan asik!
                </p>
                
                {{-- Form Pencarian Event --}}
                <form action="{{ route('home') }}" method="GET" class="flex gap-2 justify-center max-w-lg mx-auto bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/20 shadow-xl">
                    {{-- Pertahankan filter kategori jika sedang aktif --}}
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event konser, pameran..." class="input input-bordered w-full text-black border-none focus:ring-2 focus:ring-primary shadow-inner" />
                    <button type="submit" class="btn btn-primary px-8 shadow-md">Cari</button>
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