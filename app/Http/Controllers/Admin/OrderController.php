<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi/order
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'event']);

        // Search by event name or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('event', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        // Urutkan dari transaksi terbaru
        $orders = $query->latest('order_date')->paginate(15);

        return view('pages.admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail spesifik sebuah transaksi
     */
    public function show($id)
    {
        $order = Order::with(['user', 'event', 'detailOrders.tiket'])->findOrFail($id);
        
        return view('pages.admin.orders.show', compact('order'));
    }
}
