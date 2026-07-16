<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyTicketController extends Controller
{
    /**
     * Menampilkan daftar riwayat pembelian tiket untuk user yang sedang login
     */
    public function index()
    {
        // Mengambil data pesanan milik user ini saja, diurutkan dari yang terbaru
        // Eager load relasi ke event dan detailOrders.tiket
        $orders = Order::with(['event', 'detailOrders.tiket'])
                       ->where('user_id', Auth::id())
                       ->latest('order_date')
                       ->paginate(10);

        return view('pages.tickets.index', compact('orders'));
    }
}
