<x-app-layout>
    {{-- HERO SECTION --}}
    <div id="heroSection" class="relative min-h-[80vh] flex items-center justify-center overflow-hidden">

        {{-- Background: gradient default (instant) + image slides (lazy) --}}
        <div id="heroBg" style="position:absolute;inset:0;background:linear-gradient(135deg,#0f0c29 0%,#302b63 50%,#24243e 100%);z-index:0;"></div>
        @foreach($heroImages as $i => $img)
        <div class="hero-slide" data-bg="{{ $img }}" style="position:absolute;inset:0;background-size:cover;background-position:center;opacity:0;transition:opacity 1.2s ease-in-out;z-index:1;"></div>
        @endforeach

        {{-- Dark overlay for text readability --}}
        <div style="position:absolute;inset:0;background:rgba(0,0,0,0.55);z-index:2;"></div>

        {{-- Content: stacked vertically, centered --}}
        <div style="position:relative;z-index:3;width:100%;text-align:center;padding:0 1.5rem;">
            <p style="font-size:0.7rem;letter-spacing:0.3em;font-weight:600;color:#93c5fd;margin-bottom:1rem;text-transform:uppercase;text-shadow:0 1px 4px rgba(0,0,0,0.8);">
                ✦ Platform Tiket Digital Terbaik ✦
            </p>
            <h1 style="font-size:clamp(2.5rem,7vw,5rem);font-weight:900;line-height:1.15;color:white;margin-bottom:1.25rem;text-shadow:0 2px 8px rgba(0,0,0,0.9),0 4px 24px rgba(0,0,0,0.7);">
                Hi, Amankan<br><span style="color:#60a5fa;">Tiketmu</span> yuk.
            </h1>
            <p style="font-size:1.1rem;font-weight:500;color:rgba(255,255,255,0.9);max-width:480px;margin:0 auto 2.5rem;line-height:1.6;text-shadow:0 1px 6px rgba(0,0,0,0.9);">
                eTicketing: Beli tiket konser, pameran, dan festival favoritmu dengan mudah dan asik!
            </p>

            {{-- Search Form --}}
            <form action="{{ route('home') }}" method="GET"
                  class="flex flex-col sm:flex-row gap-3 w-full max-w-2xl mx-auto bg-black/35 backdrop-blur-md p-4 rounded-3xl border border-white/20 shadow-2xl">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama event konser, pameran..."
                       class="input input-lg w-full text-gray-900 bg-white border-none shadow-inner rounded-2xl">
                <button type="submit" class="btn btn-primary btn-lg rounded-2xl px-8 shadow-lg font-bold whitespace-nowrap w-full sm:w-auto">
                    Cari Event
                </button>
            </form>

            {{-- Slide dots --}}
            <div id="heroDots" style="display:flex;gap:8px;justify-content:center;margin-top:2rem;">
                @foreach($heroImages as $i => $img)
                <button onclick="goToSlide({{ $i }})"
                        class="hero-dot-btn"
                        id="dot{{ $i }}"
                        style="width:{{ $i===0?'28px':'10px' }};height:10px;border-radius:10px;border:none;cursor:pointer;transition:all 0.3s;background:{{ $i===0?'white':'rgba(255,255,255,0.4)' }};"></button>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    (function(){
        const slides = document.querySelectorAll('.hero-slide');
        const dots   = document.querySelectorAll('[id^="dot"]');
        let cur = 0, timer = null, loaded = {};

        function loadSlide(i) {
            if (loaded[i] || !slides[i]) return;
            const bg = slides[i].dataset.bg;
            if (!bg) return;
            const img = new Image();
            img.onload = function() {
                slides[i].style.backgroundImage = "url('"+bg+"')";
                loaded[i] = true;
            };
            img.src = bg;
        }

        window.goToSlide = function(i) {
            if (slides[cur]) slides[cur].style.opacity = '0';
            if (dots[cur])   { dots[cur].style.width='10px'; dots[cur].style.background='rgba(255,255,255,0.4)'; }
            cur = i;
            loadSlide(cur);
            setTimeout(function(){
                if (slides[cur]) slides[cur].style.opacity = '1';
            }, 50);
            if (dots[cur]) { dots[cur].style.width='28px'; dots[cur].style.background='white'; }
            if (timer) clearInterval(timer);
            timer = setInterval(function(){ window.goToSlide((cur+1)%slides.length); }, 5000);
        };

        // Init: load first image, then others lazily
        loadSlide(0);
        if (slides[0]) slides[0].style.opacity = '1';
        timer = setInterval(function(){ window.goToSlide((cur+1)%slides.length); }, 5000);
        setTimeout(function(){ for(let i=1;i<slides.length;i++) loadSlide(i); }, 1000);
    })();
    </script>




    <section class="max-w-7xl mx-auto py-12 px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <h2 class="text-2xl font-black uppercase italic">Event</h2>
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
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