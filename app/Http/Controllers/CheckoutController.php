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

        // 1. Buat data Order (Status awalnya pending menunggu pembayaran)
        $order = Order::create([
            'user_id' => Auth::id(),
            'event_id' => $tiket->event_id,
            'order_date' => now(),
            'total_harga' => $subtotal,
            'status' => 'pending', 
        ]);

        // 2. Buat detail order (keranjang tiketnya)
        DetailOrder::create([
            'order_id' => $order->id,
            'tiket_id' => $tiket->id,
            'jumlah' => $jumlah,
            'subtotal_harga' => $subtotal,
        ]);

        // 3. Redirect ke halaman QRIS
        return redirect()->route('checkout.payment', $order->id);
    }

    /**
     * Menampilkan halaman pembayaran QRIS dengan timer
     */
    public function payment($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->route('my-tickets.index')
                             ->with('info', 'Pesanan ini sudah diproses sebelumnya.');
        }

        return view('pages.checkout.payment', compact('order'));
    }

    /**
     * Simulasi proses pembayaran sukses
     */
    public function processPayment(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        if ($order->status === 'pending') {
            $order->status = 'paid';
            $order->save();

            // Kurangi stok tiket setelah lunas
            foreach ($order->details as $detail) {
                if ($detail->tiket && $detail->tiket->stok !== null) {
                    $detail->tiket->decrement('stok', $detail->jumlah);
                }
            }
        }

        return redirect()->route('my-tickets.index')
                         ->with('success', 'Pembayaran berhasil dikonfirmasi (Simulasi QRIS).');
    }

    /**
     * Simulasi pembayaran batal / expired
     */
    public function cancelPayment(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        if ($order->status === 'pending') {
            $order->status = 'cancelled';
            $order->save();
        }

        return redirect()->route('home')
                         ->with('error', 'Waktu pembayaran habis atau dibatalkan.');
    }
}
