<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-primary mb-2">Pusat Bantuan & Petunjuk Teknis</h1>
            <p class="text-base-content/70">Panduan lengkap menggunakan aplikasi eTicketing</p>
        </div>

        <div class="space-y-4">
            
            <div class="collapse collapse-arrow bg-base-100 shadow-md border border-base-300">
                <input type="radio" name="my-accordion-2" checked="checked" /> 
                <div class="collapse-title text-xl font-bold text-secondary">
                    1. Bagaimana cara mendaftar akun?
                </div>
                <div class="collapse-content"> 
                    <p class="mb-2">Untuk bisa membeli tiket, Anda harus memiliki akun terlebih dahulu:</p>
                    <ol class="list-decimal ml-5 space-y-1">
                        <li>Klik menu <strong>Register</strong> di bagian kanan atas layar.</li>
                        <li>Isi nama lengkap, alamat email yang valid, dan kata sandi Anda.</li>
                        <li>Klik tombol <strong>Daftar</strong>.</li>
                        <li>Anda akan langsung diarahkan ke halaman Dashboard / Beranda.</li>
                    </ol>
                </div>
            </div>

            <div class="collapse collapse-arrow bg-base-100 shadow-md border border-base-300">
                <input type="radio" name="my-accordion-2" /> 
                <div class="collapse-title text-xl font-bold text-secondary">
                    2. Tata Cara Pembelian Tiket Konser
                </div>
                <div class="collapse-content"> 
                    <p class="mb-2">Berikut adalah langkah-langkah membeli tiket event/konser:</p>
                    <ol class="list-decimal ml-5 space-y-1">
                        <li>Pilih event yang Anda inginkan di halaman utama.</li>
                        <li>Klik tombol <strong>Detail Event</strong> untuk melihat informasi lengkap.</li>
                        <li>Pada bagian <em>Daftar Tiket Tersedia</em>, pilih jenis tiket dan klik <strong>Beli Tiket</strong>.</li>
                        <li>Masukkan jumlah tiket yang ingin dibeli (maksimal 5 per transaksi), lalu klik <strong>Checkout</strong>.</li>
                        <li>Anda akan diarahkan ke halaman <strong>Simulasi Pembayaran QRIS</strong>.</li>
                        <li>Scan QR code atau klik "Simulasi Bayar Sukses" sebelum timer 15 menit habis.</li>
                        <li>Tiket berhasil dibeli dan akan muncul di menu <strong>Tiket Saya</strong>.</li>
                    </ol>
                </div>
            </div>

            <div class="collapse collapse-arrow bg-base-100 shadow-md border border-base-300">
                <input type="radio" name="my-accordion-2" /> 
                <div class="collapse-title text-xl font-bold text-secondary">
                    3. Cara Menukarkan Tiket di Lokasi
                </div>
                <div class="collapse-content"> 
                    <p class="mb-2">Saat hari H event, ikuti langkah berikut:</p>
                    <ol class="list-decimal ml-5 space-y-1">
                        <li>Buka menu <strong>Tiket Saya</strong> di aplikasi.</li>
                        <li>Tunjukkan <em>Order ID</em> atau detail tiket ke petugas jaga (Gate Keeper).</li>
                        <li>Petugas akan melakukan verifikasi di sistem dashboard admin.</li>
                        <li>Setelah diverifikasi, Anda bisa masuk ke area event.</li>
                    </ol>
                </div>
            </div>

            <div class="collapse collapse-arrow bg-base-100 shadow-md border border-base-300">
                <input type="radio" name="my-accordion-2" /> 
                <div class="collapse-title text-xl font-bold text-secondary">
                    4. Mengalami Kendala / Aplikasi Bermasalah?
                </div>
                <div class="collapse-content"> 
                    <p>Jika Anda menemukan error (misal: halaman putih, proses pembayaran gagal, dsb), sistem kami telah mencatat aktivitas tersebut secara otomatis ke dalam <strong>Log Server</strong> kami.</p>
                    <p class="mt-2">Tim teknis kami memantau aplikasi secara real-time. Anda juga dapat menghubungi support di: <a href="mailto:support@eticketing.local" class="text-primary hover:underline">support@eticketing.local</a></p>
                </div>
            </div>

        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('home') }}" class="btn btn-primary btn-outline">Kembali ke Beranda</a>
        </div>
    </div>
</x-app-layout>
