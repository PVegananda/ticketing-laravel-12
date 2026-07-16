<x-app-layout>
    {{-- Hero Section: Instant-load gradient + lazy image background --}}
    <style>
        .hero-slider {
            position: relative;
            width: 100%;
            height: 80vh;
            min-height: 560px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        /* Default instant background — no network needed */
        .hero-bg-default {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 40%, #24243e 100%);
            z-index: 1;
        }
        /* Image slides load lazily on top */
        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            z-index: 2;
        }
        .hero-slide.active { opacity: 1; }
        /* Strong dark overlay for text readability */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,0.6) 0%,
                rgba(0,0,0,0.5) 50%,
                rgba(0,0,0,0.7) 100%
            );
            z-index: 10;
        }
        .hero-content {
            position: relative;
            z-index: 20;
            text-align: center;
            color: white;
            width: 100%;
            padding: 0 1.5rem;
        }
        .hero-title {
            text-shadow: 0 2px 4px rgba(0,0,0,0.8), 0 4px 20px rgba(0,0,0,0.9);
        }
        .hero-subtitle {
            text-shadow: 0 1px 3px rgba(0,0,0,0.9), 0 2px 12px rgba(0,0,0,0.8);
        }
        /* Dot indicators */
        .hero-dots { display: flex; gap: 8px; justify-content: center; margin-top: 2rem; }
        .hero-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .hero-dot.active {
            background: white;
            width: 28px;
            border-radius: 6px;
        }
    </style>

    <div class="hero-slider" id="heroSlider">
        {{-- Instant gradient base (shows immediately, no network) --}}
        <div class="hero-bg-default"></div>

        {{-- Image slides (loaded lazily) --}}
        @foreach($heroImages as $i => $img)
        <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" id="heroSlide{{ $i }}"
             data-bg="{{ $img }}"></div>
        @endforeach

        {{-- Overlay for text contrast --}}
        <div class="hero-overlay"></div>

        {{-- Hero Content centered --}}
        <div class="hero-content">
            <p class="text-xs md:text-sm uppercase tracking-[0.35em] font-semibold mb-4 text-blue-300 hero-subtitle">
                ✦ Platform Tiket Digital Terbaik ✦
            </p>
            <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tight leading-tight hero-title">
                Hi, Amankan<br><span class="text-blue-400">Tiketmu</span> yuk.
            </h1>
            <p class="text-base md:text-xl font-semibold mb-10 text-white max-w-xl mx-auto leading-relaxed hero-subtitle">
                eTicketing: Beli tiket konser, pameran, dan festival favoritmu dengan mudah dan asik!
            </p>

            {{-- Search Form --}}
            <form action="{{ route('home') }}" method="GET"
                  class="flex flex-col md:flex-row gap-3 w-full max-w-2xl mx-auto bg-black/30 backdrop-blur-md p-4 rounded-3xl border border-white/20 shadow-2xl">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama event konser, pameran..."
                       class="input input-lg w-full text-gray-900 bg-white border-none shadow-inner rounded-2xl" />
                <button type="submit" class="btn btn-primary btn-lg rounded-2xl px-10 shadow-lg font-bold whitespace-nowrap">
                    Cari Event
                </button>
            </form>

            {{-- Dot Indicators --}}
            <div class="hero-dots" id="heroDots">
                @foreach($heroImages as $i => $img)
                <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></button>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    (function() {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        let current = 0;
        let timer = null;
        let loaded = {};

        // Preload image and set background
        function loadSlide(index) {
            if (loaded[index]) return;
            const slide = slides[index];
            const bg = slide.dataset.bg;
            if (!bg) return;
            const img = new Image();
            img.onload = function() {
                slide.style.backgroundImage = "url('" + bg + "')";
                loaded[index] = true;
            };
            img.src = bg;
        }

        function goToSlide(index) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = index;
            loadSlide(current);
            slides[current].classList.add('active');
            dots[current].classList.add('active');
            resetTimer();
        }

        function nextSlide() {
            goToSlide((current + 1) % slides.length);
        }

        function resetTimer() {
            if (timer) clearInterval(timer);
            timer = setInterval(nextSlide, 5000);
        }

        // Expose to global for onclick
        window.goToSlide = goToSlide;

        // Start: load first image immediately, preload others after
        loadSlide(0);
        resetTimer();
        setTimeout(function() {
            for (let i = 1; i < slides.length; i++) loadSlide(i);
        }, 1500);
    })();
    </script>

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