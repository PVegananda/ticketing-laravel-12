<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Models\Order;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman ringkasan checkout
     */
    public function index(Request $request)
    {
        // Pastikan ada parameter tiket_id
        $request->validate([
            'tiket_id' => 'required|exists:tikets,id'
        ]);

        // Ambil data tiket beserta event-nya
        $tiket = Tiket::with('event')->findOrFail($request->tiket_id);

        // Cek stok apakah masih ada
        if ($tiket->stok !== null && $tiket->stok <= 0) {
            return redirect()->route('events.show', $tiket->event_id)
                             ->with('error', 'Maaf, tiket ini sudah habis terjual.');
        }

        return view('pages.checkout.index', compact('tiket'));
    }

    /**
     * Memproses transaksi pembelian
     */
    public function store(Request $request)
    {
        $request->validate([
            'tiket_id' => 'required|exists:tikets,id',
            'jumlah' => 'required|integer|min:1|max:5' // Batasi maksimal beli 5 tiket sekaligus
        ]);

        $tiket = Tiket::findOrFail($request->tiket_id);
        $jumlah = $request->jumlah;

        // Validasi ulang stok tiket sebelum memproses
        if ($tiket->stok !== null && $tiket->stok < $jumlah) {
            return redirect()->back()->with('error', 'Stok tiket tidak mencukupi untuk jumlah pembelian ini.');
        }

        $subtotal = $tiket->harga * $jumlah;

        // 1. Buat data Order (Disimulasikan langsung lunas / paid)
        $order = Order::create([
            'user_id' => Auth::id(),
            'event_id' => $tiket->event_id,
            'order_date' => now(),
            'total_harga' => $subtotal,
            'status' => 'paid', 
        ]);

        // 2. Buat detail order (keranjang tiketnya)
        DetailOrder::create([
            'order_id' => $order->id,
            'tiket_id' => $tiket->id,
            'jumlah' => $jumlah,
            'subtotal_harga' => $subtotal,
        ]);

        // 3. Kurangi stok tiket
        if ($tiket->stok !== null) {
            $tiket->decrement('stok', $jumlah);
        }

        // 4. Redirect ke halaman tiket saya dengan pesan sukses
        return redirect()->route('my-tickets.index')
                         ->with('success', 'Pembelian tiket berhasil! Pembayaran otomatis disimulasikan lunas.');
    }
}
