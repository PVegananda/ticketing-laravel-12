@extends('layouts.app')

@section('title', 'Checkout Tiket')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6 min-h-screen">
    
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold">Ringkasan Pesanan</h1>
        <p class="text-gray-500">Selesaikan pembayaran untuk mengamankan tiketmu.</p>
    </div>

    {{-- Notifikasi Error --}}
    @if(session('error'))
        <div class="alert alert-error mb-6 shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error mb-6 shadow-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- Kiri: Form Pembelian --}}
        <div class="lg:w-2/3">
            <div class="card bg-base-100 shadow-xl border border-gray-100">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4 border-b pb-2">Detail Pembeli</h2>
                    
                    <div class="mb-6 space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Nama Lengkap</p>
                            <p class="font-semibold text-lg">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold text-lg">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="tiket_id" value="{{ $tiket->id }}">
                        
                        <h2 class="card-title text-xl mb-4 border-b pb-2 mt-6">Jumlah Tiket</h2>
                        
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">Berapa banyak tiket yang ingin dibeli? (Maks. 5)</span>
                            </label>
                            <select name="jumlah" id="jumlah" class="select select-bordered w-full md:max-w-xs text-lg">
                                @for($i = 1; $i <= min(5, $tiket->stok ?? 5); $i++)
                                    <option value="{{ $i }}">{{ $i }} Tiket</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="btn btn-primary w-full btn-lg">
                                Bayar Sekarang
                            </button>
                            <p class="text-center text-xs text-gray-400 mt-3">
                                *Pembayaran pada tahap ini akan disimulasikan sukses secara otomatis.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kanan: Ringkasan Event --}}
        <div class="lg:w-1/3">
            <div class="card bg-base-200 shadow-md">
                <figure>
                    <img src="{{ $tiket->event->image_url }}" alt="{{ $tiket->event->judul }}" class="h-40 w-full object-cover" />
                </figure>
                <div class="card-body p-6">
                    <h3 class="font-bold text-xl mb-2">{{ $tiket->event->judul }}</h3>
                    
                    <div class="text-sm space-y-2 mb-4 text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ \Carbon\Carbon::parse($tiket->event->tanggal_waktu ?? $tiket->event->tanggal)->locale('id')->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>{{ $tiket->event->lokasi }}</span>
                        </div>
                    </div>

                    <div class="divider my-2"></div>

                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-500">Tipe Tiket</span>
                        <span class="font-semibold">{{ ucfirst($tiket->tipe) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-500">Harga Satuan</span>
                        <span class="font-semibold">Rp <span id="harga-satuan">{{ number_format($tiket->harga, 0, ',', '') }}</span></span>
                    </div>

                    <div class="divider my-2"></div>

                    <div class="flex justify-between items-end">
                        <span class="font-bold text-lg">Total Pembayaran</span>
                        <span class="font-black text-2xl text-primary">Rp <span id="total-harga">0</span></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahSelect = document.getElementById('jumlah');
    const hargaSatuan = parseInt(document.getElementById('harga-satuan').innerText);
    const totalHargaElement = document.getElementById('total-harga');

    // Fungsi format rupiah
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Fungsi update total
    function updateTotal() {
        const jumlah = parseInt(jumlahSelect.value) || 1;
        const total = hargaSatuan * jumlah;
        totalHargaElement.innerText = formatRupiah(total);
    }

    // Panggil saat pertama kali load
    updateTotal();

    // Panggil saat dropdown diubah
    jumlahSelect.addEventListener('change', updateTotal);
});
</script>
@endsection
