<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventFormRequest;
use App\Models\Kategori;
use App\Models\Tiket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * EventController
 *
 * Controller ini menghandle semua operasi CRUD (Create, Read, Update, Delete)
 * untuk data Event di aplikasi ticketing ini.
 *
 * Alur kerja:
 * - Admin bisa tambah event baru lewat form (create -> store)
 * - Admin bisa lihat daftar event dengan filter & search (index)
 * - Admin bisa edit event yang sudah ada (edit -> update)
 * - Admin bisa hapus event, KECUALI jika event sudah ada penjualannya (destroy)
 * - Publik bisa lihat detail event beserta tiket & event terkait (show)
 */
class EventController extends Controller
{
    /**
     * INDEX - Tampilkan daftar semua event untuk halaman admin
     *
     * Fitur yang ada:
     * - Filter berdasarkan kategori
     * - Pencarian berdasarkan judul atau lokasi
     * - Pengurutan berdasarkan tanggal (asc = terlama, desc = terbaru)
     * - Pagination 10 item per halaman
     *
     * URL: GET /admin/events
     */
    public function index(Request $request)
    {
        // Ambil data event beserta relasinya (kategori dan tiket)
        // with() = eager loading, supaya tidak terjadi N+1 query
        $events = Event::with(['kategori', 'tikets'])

            // Filter berdasarkan kategori jika parameter kategori_id ada di URL
            // Contoh URL: /admin/events?kategori_id=2
            ->when($request->kategori_id, function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori_id);
            })

            // Pencarian berdasarkan judul atau lokasi jika parameter search ada di URL
            // Contoh URL: /admin/events?search=konser
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
                });
            })

            // Urutkan berdasarkan tanggal event (default: asc = terlama dulu)
            // Contoh URL: /admin/events?sort=desc untuk terbaru dulu
            ->orderBy('tanggal_waktu', $request->get('sort', 'asc'))

            // Pagination 10 item per halaman
            ->paginate(10);

        // Ambil semua kategori untuk dropdown filter di halaman index
        $kategoris = Kategori::all();

        // Kirim data ke view admin events index
        return view('pages.admin.events.index', compact('events', 'kategoris'));
    }

    /**
     * CREATE - Tampilkan form tambah event baru
     *
     * URL: GET /admin/events/create
     */
    public function create()
    {
        // Ambil semua kategori untuk ditampilkan pada dropdown di form
        $kategoris = Kategori::all();

        // Tampilkan view form tambah event
        return view('pages.admin.events.create', compact('kategoris'));
    }

    /**
     * STORE - Simpan event baru ke database
     *
     * Menggunakan EventFormRequest untuk validasi otomatis sebelum data masuk ke method ini.
     * Jika validasi gagal, Laravel otomatis redirect balik ke form dengan pesan error.
     *
     * URL: POST /admin/events
     */
    public function store(EventFormRequest $request)
    {
        // Ambil data yang sudah tervalidasi dari EventFormRequest
        $validated = $request->validated();

        // Handle upload gambar ke storage (folder 'events' di disk 'public')
        // Jika tidak ada gambar yang diupload, gunakan gambar default 'konser.jpg'
        if ($request->hasFile('gambar')) {
            // store() menyimpan file ke storage/app/public/events/
            // dan mengembalikan path relatifnya, contoh: events/AbcXyz.jpg
            $validated['gambar'] = $request->file('gambar')->store('events', 'public');
        } else {
            $validated['gambar'] = 'konser.jpg'; // gambar default
        }

        // Simpan event baru ke database
        // user_id diambil dari user yang sedang login (Auth::id())
        $event = Event::create([
            'user_id'      => Auth::id(),
            'kategori_id'  => $validated['kategori_id'],
            'judul'        => $validated['judul'],
            'deskripsi'    => $validated['deskripsi'],
            'lokasi'       => $validated['lokasi'],
            'gambar'       => $validated['gambar'],
            'tanggal_waktu' => $validated['tanggal_waktu'],
        ]);

        // Simpan seluruh tiket yang dikirim dari form secara dinamis
        // tikets adalah array, contoh: tikets[0][tipe], tikets[0][harga], tikets[0][stok]
        foreach ($validated['tikets'] as $tiket) {
            $event->tikets()->create([
                'tipe'  => $tiket['tipe'],
                'harga' => $tiket['harga'],
                'stok'  => $tiket['stok'],
            ]);
        }

        // Redirect ke halaman daftar event dengan pesan sukses
        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * EDIT - Tampilkan form edit event yang sudah ada
     *
     * URL: GET /admin/events/{event}/edit
     * {event} otomatis di-resolve oleh Laravel menjadi object Event (Route Model Binding)
     */
    public function edit(Event $event)
    {
        // Load relasi kategori dan tiket supaya bisa ditampilkan di form
        $event->load('tikets', 'kategori');

        // Ambil semua kategori untuk dropdown
        $kategoris = Kategori::all();

        // Cek apakah event sudah pernah ada penjualan tiket
        // Jika iya, beberapa field tidak bisa diubah (contoh: tanggal)
        $hasSales = $event->hasSales();

        // Tampilkan view form edit dengan data event, kategori, dan status penjualan
        return view('pages.admin.events.edit', compact(
            'event',
            'kategoris',
            'hasSales'
        ));
    }

    /**
     * UPDATE - Simpan perubahan event ke database
     *
     * URL: PUT /admin/events/{event}
     * Validasi dilakukan oleh EventFormRequest sebelum masuk ke method ini.
     */
    public function update(EventFormRequest $request, Event $event)
    {
        // Ambil data yang sudah tervalidasi
        $validated = $request->validated();

        // Proteksi: Jika event sudah ada penjualan, tanggal tidak boleh diubah
        // Ini untuk menjaga integritas data pembelian tiket yang sudah terjadi
        if (
            $event->hasSales() &&
            $validated['tanggal_waktu'] != $event->tanggal_waktu->format('Y-m-d H:i:s')
        ) {
            return back()->withErrors([
                'tanggal_waktu' => 'Tanggal event tidak dapat diubah karena sudah memiliki penjualan tiket.'
            ])->withInput();
        }

        // Handle update gambar: hanya update jika ada file baru yang diupload
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama dari storage (kecuali gambar default 'konser.jpg')
            if (
                $event->gambar &&
                $event->gambar !== 'konser.jpg' &&
                Storage::disk('public')->exists($event->gambar)
            ) {
                Storage::disk('public')->delete($event->gambar);
            }

            // Upload gambar baru dan simpan pathnya
            $validated['gambar'] = $request
                ->file('gambar')
                ->store('events', 'public');
        } else {
            // Jika tidak ada gambar baru, pertahankan gambar lama
            $validated['gambar'] = $event->gambar;
        }

        // Update data utama event di database
        $event->update([
            'kategori_id'   => $validated['kategori_id'],
            'judul'         => $validated['judul'],
            'deskripsi'     => $validated['deskripsi'],
            'lokasi'        => $validated['lokasi'],
            'gambar'        => $validated['gambar'],
            'tanggal_waktu' => $validated['tanggal_waktu'],
        ]);

        // Array untuk menyimpan ID tiket yang masih digunakan setelah update
        $ticketIds = [];

        // Loop seluruh tiket dari form
        foreach ($validated['tikets'] as $tiket) {

            // Jika tiket sudah memiliki ID = tiket lama yang perlu diupdate
            if (!empty($tiket['id'])) {

                $existingTicket = Tiket::find($tiket['id']);

                if ($existingTicket) {
                    $existingTicket->update([
                        'tipe'  => $tiket['tipe'],
                        'harga' => $tiket['harga'],
                        'stok'  => $tiket['stok'],
                    ]);

                    // Catat ID tiket yang masih ada
                    $ticketIds[] = $existingTicket->id;
                }

            } else {

                // Jika tidak ada ID = tiket baru, langsung dibuat
                $newTicket = $event->tikets()->create([
                    'tipe'  => $tiket['tipe'],
                    'harga' => $tiket['harga'],
                    'stok'  => $tiket['stok'],
                ]);

                $ticketIds[] = $newTicket->id;
            }
        }

        // Hapus tiket yang tidak lagi ada di form (sudah dihapus user)
        // Tapi hanya jika event belum ada penjualan (proteksi data)
        if (!$event->hasSales()) {
            $event->tikets()
                ->whereNotIn('id', $ticketIds)
                ->delete();
        }

        // Redirect ke daftar event dengan pesan sukses
        return redirect()
        ->route('admin.events.index')
        ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * DESTROY - Hapus event dari database
     *
     * URL: DELETE /admin/events/{event}
     * CATATAN PENTING: Event yang sudah ada penjualan TIDAK BISA dihapus!
     * Ini untuk menjaga data historis transaksi tetap utuh.
     */
    public function destroy(Event $event)
    {
        // Cek dulu apakah event sudah ada penjualan tiket
        // Jika iya, tolak penghapusan dan tampilkan pesan error
        if ($event->hasSales()) {
            return redirect()
                ->route('admin.events.index')
                ->with('error', 'Event tidak dapat dihapus karena sudah memiliki penjualan tiket.');
        }

        // Hapus file gambar dari storage (kecuali gambar default)
        if (
            $event->gambar &&
            $event->gambar !== 'konser.jpg' &&
            Storage::disk('public')->exists($event->gambar)
        ) {
            Storage::disk('public')->delete($event->gambar);
        }

        // Hapus event dari database
        // Tiket akan ikut terhapus otomatis karena ada cascade delete di migration
        $event->delete();

        // Redirect ke daftar event dengan pesan sukses
        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * SHOW - Tampilkan detail event untuk halaman publik
     *
     * URL: GET /events/{event}
     * Halaman ini bisa diakses siapa saja (tidak perlu login).
     * Menampilkan detail event + daftar tiket + event terkait (same category).
     */
    public function show(Event $event)
    {
        // Load relasi yang dibutuhkan untuk halaman detail
        $event->load(['kategori', 'tikets']);

        // Ambil event terkait:
        // - Kategori sama dengan event yang sedang ditampilkan
        // - Bukan event yang sedang ditampilkan itu sendiri (where id != event ini)
        // - Hanya event yang akan datang (scope upcoming())
        // - Maksimal 4 event untuk ditampilkan di grid
        $relatedEvents = Event::with('kategori')
            ->where('kategori_id', $event->kategori_id)  // same category
            ->where('id', '!=', $event->id)              // bukan event ini sendiri
            ->upcoming()                                   // hanya yang akan datang
            ->limit(4)                                     // max 4 event
            ->get();

        // Kirim data ke view detail event
        return view('events.show', [
            'event'         => $event,
            'relatedEvents' => $relatedEvents, // event terkait untuk section "Event Terkait"
        ]);
    }
}