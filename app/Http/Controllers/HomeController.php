<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage with events and categories.
     */
    public function index(Request $request)
    {
        // Get all categories for the filter pills
        $categories = Kategori::all();

        // Build event query
        $eventsQuery = Event::with(['kategori', 'tikets']);

        // Filter by category if specified
        if ($request->has('kategori') && $request->kategori) {
            $eventsQuery->where('kategori_id', $request->kategori);
        }

        // Search by event title if specified
        if ($request->has('search') && $request->search) {
            $eventsQuery->where('judul', 'like', '%' . $request->search . '%');
        }

        // Get events with minimum ticket price
        $events = $eventsQuery->get()->map(function ($event) {
            // Add minimum ticket price to each event
            $event->tikets_min_harga = $event->tikets->min('harga') ?? 0;
            return $event;
        });

        return view('home', [
            'categories' => $categories,
            'events' => $events,
            'heroImages' => Event::whereNotNull('gambar')->take(3)->pluck('gambar')->map(function ($url) {
                // Compress Unsplash images for faster loading
                if (str_contains($url, 'unsplash.com')) {
                    $url = preg_replace('/q=\d+/', 'q=30', $url);
                    $url = preg_replace('/w=\d+/', 'w=1280', $url);
                }
                return $url;
            })->values(),
        ]);
    }
}
