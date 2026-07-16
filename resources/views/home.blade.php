<x-app-layout>
    {{-- Hero Section dengan Slider --}}
    <div x-data="{ activeSlide: 0, init() { setInterval(() => { this.activeSlide = (this.activeSlide + 1) % 3; }, 5000); } }" 
         class="relative w-full min-h-[80vh] flex items-center justify-center overflow-hidden">
        
        {{-- Slide 1 --}}
        <img x-show="activeSlide === 0" x-transition:enter="transition-opacity duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-1000" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            src="{{ asset('assets/images/hero1.jpg') }}" 
            class="absolute inset-0 w-full h-full object-cover" alt="Hero 1" style="display:block;">

        {{-- Slide 2 --}}
        <img x-show="activeSlide === 1" x-transition:enter="transition-opacity duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-1000" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            src="{{ asset('assets/images/hero2.jpg') }}" 
            class="absolute inset-0 w-full h-full object-cover" alt="Hero 2" style="display:none;">

        {{-- Slide 3 --}}
        <img x-show="activeSlide === 2" x-transition:enter="transition-opacity duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-1000" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            src="{{ asset('assets/images/hero3.jpg') }}" 
            class="absolute inset-0 w-full h-full object-cover" alt="Hero 3" style="display:none;">

        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/30 z-10"></div>

        {{-- Hero Content --}}
        <div class="relative z-20 text-center text-white px-6 w-full max-w-4xl">
            <p class="text-sm uppercase tracking-[0.3em] font-semibold mb-3 text-primary">✦ Platform Tiket Digital Terbaik ✦</p>
            <h1 class="text-5xl md:text-7xl font-black drop-shadow-2xl mb-6 tracking-tight leading-tight">
                Hi, Amankan<br><span class="text-primary">Tiketmu</span> yuk.
            </h1>
            <p class="text-lg md:text-xl font-medium drop-shadow-lg mb-10 text-gray-300 max-w-2xl mx-auto">
                eTicketing: Beli tiket konser, pameran, dan festival favoritmu dengan mudah dan asik!
            </p>
            
            {{-- Form Pencarian Event --}}
            <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-3 justify-center max-w-2xl mx-auto bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/20 shadow-2xl">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event konser, pameran..." class="input input-bordered input-lg w-full text-black border-none shadow-inner rounded-2xl" />
                <button type="submit" class="btn btn-primary btn-lg rounded-2xl px-10 shadow-lg font-bold">🔍 Cari</button>
            </form>

            {{-- Slide Indicators --}}
            <div class="flex gap-2 justify-center mt-8">
                <button @click="activeSlide = 0" :class="activeSlide === 0 ? 'w-8 bg-primary' : 'w-3 bg-white/50'" class="h-3 rounded-full transition-all duration-300"></button>
                <button @click="activeSlide = 1" :class="activeSlide === 1 ? 'w-8 bg-primary' : 'w-3 bg-white/50'" class="h-3 rounded-full transition-all duration-300"></button>
                <button @click="activeSlide = 2" :class="activeSlide === 2 ? 'w-8 bg-primary' : 'w-3 bg-white/50'" class="h-3 rounded-full transition-all duration-300"></button>
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