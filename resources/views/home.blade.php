<x-app-layout>
    {{-- Hero Section dengan Slider AlpineJS --}}
    <div x-data="{
            activeSlide: 0,
            slides: [
                'https://images.unsplash.com/photo-1540039155732-6761b54f2222?q=80&w=1920&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?q=80&w=1920&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=1920&auto=format&fit=crop'
            ],
            init() {
                setInterval(() => {
                    this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1;
                }, 4000);
            }
        }" 
        class="relative w-full min-h-[75vh] flex items-center justify-center overflow-hidden">
        
        {{-- Background Images --}}
        <template x-for="(slide, index) in slides" :key="index">
            <img 
                :src="slide" 
                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out"
                :class="activeSlide === index ? 'opacity-100' : 'opacity-0'"
                alt="Event Background">
        </template>

        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-base-100 z-10"></div>

        {{-- Hero Content --}}
        <div class="relative z-20 text-center text-white px-6 w-full max-w-4xl">
            <h1 class="text-5xl md:text-6xl font-black drop-shadow-2xl mb-4 tracking-tight">Hi, Amankan Tiketmu yuk.</h1>
            <p class="text-lg md:text-xl font-medium drop-shadow-lg mb-8 text-gray-200">
                eTicketing: Beli tiket konser, pameran, dan festival favoritmu dengan mudah dan asik!
            </p>
            
            {{-- Form Pencarian Event --}}
            <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-2 justify-center max-w-2xl mx-auto bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/20 shadow-2xl">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event konser, pameran..." class="input input-bordered input-lg w-full text-black border-none focus:ring-2 focus:ring-primary shadow-inner rounded-2xl" />
                <button type="submit" class="btn btn-primary btn-lg rounded-2xl px-10 shadow-lg text-lg">Cari</button>
            </form>
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