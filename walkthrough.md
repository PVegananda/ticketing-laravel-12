# Walkthrough: Fitur Checkout, Tiket Saya & Manajemen Transaksi

Semua fitur yang ada pada rencana telah selesai diimplementasikan satu persatu. Berikut adalah ringkasan fitur yang telah selesai:

## 1. Fitur Pencarian & Filter Kategori di Beranda (Home)
- Menambahkan **Form Search** di Hero section pada halaman utama yang memungkinkan pembeli untuk mencari event berdasarkan judul atau lokasinya.
- Nilai form pencarian tetap dipertahankan saat pengunjung juga menggunakan filter kategori, sehingga pencarian dan filter kategori bisa dikombinasikan.

## 2. Fitur Pembelian Tiket (Checkout)
- **Database**: Menambahkan kolom `status` pada tabel `orders` untuk membedakan transaksi yang sudah dibayar (lunas) dengan yang tertunda (pending) (commit terpisah).
- **Tombol Beli**: Mengarahkan tombol "Beli Sekarang" dari halaman detail event langsung ke halaman ringkasan checkout.
- **Halaman Checkout**: Menampilkan detail pembelian, termasuk gambar event, harga, dropdown pilihan kuantitas maksimal (1-5), detail user, dan perhitungan subtotal dinamis. Form dikelola oleh `CheckoutController`. 

## 3. Fitur "Tiket Saya" (Riwayat Pembelian User)
- Menambahkan menu navigasi "Tiket Saya" di bawah dropdown profile (bagi pembeli/user yang sudah login).
- Menambahkan **Halaman Tiket Saya** (`MyTicketController`) yang menampilkan list tiket yang sudah dibeli dengan informasi status lunas/pending.

## 4. Fitur Manajemen Transaksi Admin
- Admin sekarang memiliki menu baru di sidebar bernama **Manajemen Transaksi**.
- **Daftar Transaksi**: Menampilkan seluruh transaksi yang pernah dilakukan oleh pembeli beserta informasi total pendapatan dari satu order. Terdapat juga fitur pencarian nama atau nama event.
- **Detail Transaksi**: Halaman rincian order, yang menampilkan daftar tiket apa saja yang dibeli (satu order bisa beli lebih dari 1 tiket) dan rincian data pembeli.

Semua perubahan sudah **di-commit** sesuai instruksimu, file-per-file atau fitur-per-fitur agar riwayat git-nya mudah dipahami.
Kamu sudah bisa langsung mencobanya melalui browser (pastikan login dengan Role Pembeli untuk beli tiket, dan login dengan Admin untuk melihat transaksi).
