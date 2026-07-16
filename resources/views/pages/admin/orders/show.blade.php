@extends('layouts.admin_layouts')

@section('title', 'Detail Transaksi')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Transaksi #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-gray-500">Rincian lengkap dari pembelian tiket.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">← Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Info Pembeli & Transaksi --}}
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h3 class="card-title border-b pb-2 mb-4">Informasi Pembeli & Transaksi</h3>
                
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th class="w-1/3 text-gray-500">Tanggal Transaksi</th>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('l, d F Y - H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="text-gray-500">Status Pembayaran</th>
                            <td>
                                @if($order->status == 'paid')
                                    <span class="badge badge-success text-white font-bold">Lunas</span>
                                @else
                                    <span class="badge badge-warning text-white">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-gray-500">Nama Pembeli</th>
                            <td class="font-bold">{{ $order->user->name ?? 'User Terhapus' }}</td>
                        </tr>
                        <tr>
                            <th class="text-gray-500">Email Pembeli</th>
                            <td>{{ $order->user->email ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Info Event --}}
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h3 class="card-title border-b pb-2 mb-4">Informasi Event</h3>
                
                @if($order->event)
                    <div class="flex gap-4">
                        <img src="{{ $order->event->image_url }}" alt="Event" class="w-24 h-24 rounded object-cover shadow-sm">
                        <div>
                            <h4 class="font-bold text-lg line-clamp-1">{{ $order->event->judul }}</h4>
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="block">📅 {{ \Carbon\Carbon::parse($order->event->tanggal_waktu ?? now())->translatedFormat('d M Y') }}</span>
                                <span class="block mt-1">📍 {{ $order->event->lokasi }}</span>
                            </p>
                        </div>
                    </div>
                @else
                    <div class="alert alert-error">
                        Informasi event sudah tidak tersedia atau telah dihapus permanen.
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Daftar Item Tiket --}}
    <div class="card bg-base-100 shadow mt-6">
        <div class="card-body p-0">
            <h3 class="card-title p-6 border-b pb-2 m-0">Rincian Tiket Dibeli</h3>
            
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Tipe Tiket</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Jumlah Beli</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->detailOrders as $detail)
                            <tr>
                                <td class="font-bold text-lg">Tiket {{ ucfirst($detail->tiket->tipe ?? 'Tipe Tidak Diketahui') }}</td>
                                <td>Rp {{ number_format($detail->tiket->harga ?? 0, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge badge-lg">{{ $detail->jumlah }}</span>
                                </td>
                                <td class="text-right font-semibold">Rp {{ number_format($detail->subtotal_harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-base-50">
                        <tr>
                            <td colspan="3" class="text-right font-bold text-lg">Total Pembayaran</td>
                            <td class="text-right font-black text-2xl text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
