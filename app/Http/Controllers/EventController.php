<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventFormRequest;
use App\Models\Kategori;
use App\Models\Tiket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * function index(Request $request)
     */
    public function index(Request $request)
    {
        // Ambil data event beserta relasinya
        $events = Event::with(['kategori', 'tikets'])
            // Filter berdasarkan kategori
            ->when($request->kategori_id, function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori_id);
            })
            // Pencarian berdasarkan judul atau lokasi
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
                });
            })
            // Urutkan berdasarkan tanggal event
            ->orderBy('tanggal_waktu', $request->get('sort', 'asc'))
            ->paginate(10);

        // Ambil semua kategori untuk dropdown filter
        $kategoris = Kategori::all();

        return view('pages.admin.events.index', compact('events', 'kategoris'));
    }

    /**
     * Create Event
     */
    public function create()
    {
        // Ambil semua kategori untuk ditampilkan pada dropdown
        $kategoris = Kategori::all();

        return view('pages.admin.events.create', compact('kategoris'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(EventFormRequest $request)
    {
        // Ambil data yang sudah tervalidasi
        $validated = $request->validated();

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('events', 'public');
        } else {
            $validated['gambar'] = 'konser.jpg';
        }

        // Simpan event baru
        $event = Event::create([
            'user_id' => Auth::id(),
            'kategori_id' => $validated['kategori_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'gambar' => $validated['gambar'],
            'tanggal_waktu' => $validated['tanggal_waktu'],
        ]);

        // Simpan seluruh tiket yang dikirim dari form
        foreach ($validated['tikets'] as $tiket) {
            $event->tikets()->create([
                'tipe' => $tiket['tipe'],
                'harga' => $tiket['harga'],
                'stok' => $tiket['stok'],
            ]);
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // Load relasi kategori dan tiket
        $event->load('tikets', 'kategori');

        // Ambil semua kategori untuk dropdown
        $kategoris = Kategori::all();

        // Cek apakah event sudah memiliki penjualan
        $hasSales = $event->hasSales();

        return view('pages.admin.events.edit', compact(
            'event',
            'kategoris',
            'hasSales'
        ));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(EventFormRequest $request, Event $event)
    {
        // Ambil data yang sudah tervalidasi
        $validated = $request->validated();

        // Cek apakah event sudah memiliki penjualan
        if (
            $event->hasSales() &&
            $validated['tanggal_waktu'] != $event->tanggal_waktu->format('Y-m-d H:i:s')
        ) {
            return back()->withErrors([
                'tanggal_waktu' => 'Tanggal event tidak dapat diubah karena sudah memiliki penjualan tiket.'
            ])->withInput();
        }

        // Handle update gambar
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika bukan gambar default
            if (
                $event->gambar &&
                $event->gambar !== 'konser.jpg' &&
                Storage::disk('public')->exists($event->gambar)
            ) {
                Storage::disk('public')->delete($event->gambar);
            }

            $validated['gambar'] = $request
                ->file('gambar')
                ->store('events', 'public');
        } else {
            $validated['gambar'] = $event->gambar;
        }
        // Update data event
        $event->update([
            'kategori_id' => $validated['kategori_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'gambar' => $validated['gambar'],
            'tanggal_waktu' => $validated['tanggal_waktu'],
        ]);
        // Menyimpan ID tiket yang masih digunakan
        $ticketIds = [];

        // Loop seluruh tiket dari form
        foreach ($validated['tikets'] as $tiket) {

            // Jika tiket sudah memiliki ID, berarti update tiket lama
            if (!empty($tiket['id'])) {

                $existingTicket = Tiket::find($tiket['id']);

                if ($existingTicket) {
                    $existingTicket->update([
                        'tipe' => $tiket['tipe'],
                        'harga' => $tiket['harga'],
                        'stok' => $tiket['stok'],
                    ]);

                    $ticketIds[] = $existingTicket->id;
                }

            } else {

                // Jika tidak memiliki ID, berarti tiket baru
                $newTicket = $event->tikets()->create([
                    'tipe' => $tiket['tipe'],
                    'harga' => $tiket['harga'],
                    'stok' => $tiket['stok'],
                ]);

                $ticketIds[] = $newTicket->id;
            }
        }
        // Hapus tiket yang tidak lagi ada di form
        if (!$event->hasSales()) {

            $event->tikets()
                ->whereNotIn('id', $ticketIds)
                ->delete();
        }

        

        return redirect()
        ->route('admin.events.index')
        ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Cek apakah event sudah memiliki penjualan
        if ($event->hasSales()) {
            return redirect()
                ->route('admin.events.index')
                ->with('error', 'Event tidak dapat dihapus karena sudah memiliki penjualan tiket.');
        }

        // Hapus gambar jika bukan gambar default
        if (
            $event->gambar &&
            $event->gambar !== 'konser.jpg' &&
            Storage::disk('public')->exists($event->gambar)
        ) {
            Storage::disk('public')->delete($event->gambar);
        }

        // Hapus event (tiket akan ikut terhapus karena cascade)
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Load relasi event
        $event->load(['kategori', 'tikets']);

        // Ambil event terkait dengan kategori yang sama
        $relatedEvents = Event::with('kategori')
            ->where('kategori_id', $event->kategori_id)
            ->where('id', '!=', $event->id)
            ->upcoming()
            ->limit(4)
            ->get();

        return view('events.show', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
        ]);
    }
}