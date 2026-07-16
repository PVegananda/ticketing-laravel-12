@extends('layouts.admin_layouts')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold">Data Transaksi Pembelian</h2>
            <p class="text-gray-500">Daftar seluruh tiket yang telah dibeli oleh user.</p>
        </div>
        
        {{-- Form Pencarian --}}
        <form action="{{ route('admin.orders.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pembeli atau event..." class="input input-bordered w-full sm:w-64" />
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Tanggal Transaksi</th>
                        <th>Pembeli</th>
                        <th>Event</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td class="font-mono">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y H:i') }}</td>
                            <td>
                                <div class="font-bold">{{ $order->user->name ?? 'User Terhapus' }}</div>
                                <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="line-clamp-1 max-w-[200px]" title="{{ $order->event->judul ?? '' }}">
                                    {{ $order->event->judul ?? 'Event Terhapus' }}
                                </div>
                            </td>
                            <td class="font-semibold text-primary">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($order->status == 'paid')
                                    <span class="badge badge-success text-white">Lunas</span>
                                @else
                                    <span class="badge badge-warning text-white">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                Belum ada data transaksi pembelian tiket.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

</div>
@endsection
