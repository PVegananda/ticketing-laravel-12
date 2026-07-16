<x-app-layout>
    {{-- Hero Section dengan CSS Slider --}}
    <style>
        .hero-slider { position: relative; width: 100%; height: 80vh; overflow: hidden; }
        .hero-slide { position: absolute; inset: 0; background-size: cover; background-position: center; opacity: 0; animation: heroFade 15s infinite; }
        @php $count = count($heroImages); @endphp
        @for($i = 0; $i < $count; $i++)
        .hero-slide:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 5 }}s; }
        @endfor
        @keyframes heroFade {
            0%   { opacity: 0; }
            5%   { opacity: 1; }
            30%  { opacity: 1; }
            35%  { opacity: 0; }
            100% { opacity: 0; }
        }
        .hero-overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 10; }
        .hero-content { position: relative; z-index: 20; text-align: center; color: white; padding: 0 1.5rem; }
    </style>

    <div class="hero-slider">
        @foreach($heroImages as $img)
        <div class="hero-slide" style="background-image: url('{{ $img }}');"></div>
        @endforeach

        <div class="hero-overlay"></div>

        <div class="hero-content flex flex-col items-center justify-center h-full">
            <p class="text-xs md:text-sm uppercase tracking-[0.35em] font-semibold mb-4 text-primary/90 drop-shadow">✦ Platform Tiket Digital Terbaik ✦</p>
            <h1 class="text-5xl md:text-7xl font-black drop-shadow-2xl mb-6 tracking-tight leading-tight">
                Hi, Amankan<br><span class="text-primary">Tiketmu</span> yuk.
            </h1>
            <p class="text-base md:text-xl font-medium drop-shadow-lg mb-10 text-white/90 max-w-xl mx-auto leading-relaxed">
                eTicketing: Beli tiket konser, pameran, dan festival<br>favoritmu dengan mudah dan asik!
            </p>

            {{-- Form Pencarian Event --}}
            <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full max-w-2xl mx-auto bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/20 shadow-2xl">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event konser, pameran..." class="input input-bordered input-lg w-full text-gray-800 border-none shadow-inner rounded-2xl" />
                <button type="submit" class="btn btn-primary btn-lg rounded-2xl px-10 shadow-lg font-bold">Cari</button>
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