<x-app-layout>
<div class="max-w-7xl mx-auto py-12 px-6 min-h-screen">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold">Tiket Saya</h1>
            <p class="text-gray-500">Daftar riwayat pembelian tiket event kamu.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary">Cari Event Lain</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-lg mb-6">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <div class="card bg-base-100 shadow-xl border border-gray-100 relative overflow-hidden">
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-4 right-4 z-10">
                        @if($order->status == 'paid')
                            <span class="badge badge-success font-bold text-white shadow-sm">Lunas</span>
                        @elseif($order->status == 'pending')
                            <span class="badge badge-warning font-bold text-white shadow-sm">Pending</span>
                        @else
                            <span class="badge badge-error font-bold text-white shadow-sm">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>

                    <figure>
                        <img src="{{ $order->event->image_url ?? '' }}" alt="{{ $order->event->judul ?? '' }}" class="h-48 w-full object-cover" />
                    </figure>
                    
                    <div class="card-body">
                        <h2 class="card-title text-xl line-clamp-1" title="{{ $order->event->judul ?? 'Event Terhapus' }}">
                            {{ $order->event->judul ?? 'Event Telah Dihapus' }}
                        </h2>
                        
                        <div class="text-sm text-gray-500 mb-4 space-y-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ \Carbon\Carbon::parse($order->event->tanggal_waktu ?? now())->locale('id')->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="line-clamp-1">{{ $order->event->lokasi ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="bg-base-200 rounded-lg p-3 text-sm">
                            <p class="text-gray-500 text-xs mb-1">Rincian Tiket:</p>
                            @foreach($order->detailOrders as $detail)
                                <div class="flex justify-between items-center font-medium">
                                    <span>{{ $detail->jumlah }}x {{ ucfirst($detail->tiket->tipe ?? 'Tiket') }}</span>
                                    <span>Rp {{ number_format($detail->subtotal_harga, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="divider my-1"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Total Bayar</span>
                            <span class="font-bold text-lg text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-400 text-xs">Tgl Pesan: {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</span>
                            <span class="text-gray-400 text-xs">Order ID: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-20 bg-base-100 rounded-xl shadow-sm border border-gray-100">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Kamu belum memiliki tiket</h3>
            <p class="text-gray-500 mb-6">Yuk jelajahi event menarik dan beli tiket pertamamu sekarang!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Mulai Cari Event</a>
        </div>
    @endif

</div>
</x-app-layout>
