<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="card bg-base-100 shadow-xl border border-primary/20">
            <div class="card-body items-center text-center">
                
                <h2 class="card-title text-3xl font-black mb-2 text-primary">Menunggu Pembayaran</h2>
                <p class="text-base-content/70 mb-6">Selesaikan pembayaran sebelum waktu habis agar pesanan tidak dibatalkan otomatis.</p>
                
                {{-- Detail Order Ringkas --}}
                <div class="w-full max-w-md bg-base-200 rounded-xl p-4 mb-6 text-left">
                    <h3 class="font-bold text-lg border-b pb-2 mb-2">Detail Transaksi</h3>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-500">Order ID</span>
                        <span class="font-mono font-semibold">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-500">Total Tagihan</span>
                        <span class="font-bold text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Timer Countdown --}}
                <div class="flex gap-5 mb-8">
                    <div>
                        <span class="countdown font-mono text-5xl text-error" id="timer-minutes">
                            <span style="--value:15;"></span>
                        </span>
                        Menit
                    </div> 
                    <div>
                        <span class="countdown font-mono text-5xl text-error" id="timer-seconds">
                            <span style="--value:0;"></span>
                        </span>
                        Detik
                    </div>
                </div>

                {{-- QRIS Dummy --}}
                <div class="mb-8">
                    <div class="bg-white p-4 rounded-xl shadow-inner border border-gray-200 inline-block">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS Dummy" class="w-48 h-48 opacity-80 mix-blend-multiply">
                        <p class="text-center font-bold text-blue-800 mt-2 tracking-widest">QRIS GIMMICK</p>
                    </div>
                </div>

                {{-- Aksi Simulasi --}}
                <div class="flex flex-col sm:flex-row gap-4 w-full max-w-md">
                    <form action="{{ route('checkout.cancel', $order->id) }}" method="POST" class="w-full" id="cancelForm">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-error w-full">Batalkan</button>
                    </form>

                    <form action="{{ route('checkout.process', $order->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full">Simulasi Bayar Sukses</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set waktu mundur: 15 menit
            let totalSeconds = 15 * 60;
            const minEl = document.querySelector('#timer-minutes span');
            const secEl = document.querySelector('#timer-seconds span');
            const cancelForm = document.getElementById('cancelForm');

            const timer = setInterval(() => {
                totalSeconds--;
                
                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;

                minEl.style.setProperty('--value', minutes);
                secEl.style.setProperty('--value', seconds);

                if(totalSeconds <= 0) {
                    clearInterval(timer);
                    // Submit otomatis form batal ketika waktu habis
                    cancelForm.submit();
                }
            }, 1000);
        });
    </script>
</x-app-layout>
