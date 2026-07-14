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
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Load the event with its relationships
        $event->load(['kategori', 'tikets']);

        return view('events.show', [
            'event' => $event,
        ]);
    }
}