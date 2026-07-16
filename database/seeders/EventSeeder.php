<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'user_id' => 2, // Admin User
                'judul' => 'Konser Musik Rock Tanah Air',
                'deskripsi' => 'Nikmati malam penuh energi dengan band rock terkenal dari seluruh Indonesia. Tersedia berbagai stand makanan dan minuman.',
                'tanggal_waktu' => Carbon::now()->addDays(15)->format('Y-m-d H:i:s'),
                'lokasi' => 'Stadion Utama Gelora Bung Karno, Jakarta',
                'kategori_id' => 1,
                'gambar' => 'https://images.unsplash.com/photo-1540039155733-d7f58f249d05?q=80&w=2069&auto=format&fit=crop',
            ],
            [
                'user_id' => 2,
                'judul' => 'Pameran Seni Kontemporer 2026',
                'deskripsi' => 'Jelajahi karya seni modern dari seniman lokal dan internasional yang akan membuka wawasan Anda tentang dunia seni digital dan fisik.',
                'tanggal_waktu' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'),
                'lokasi' => 'Galeri Nasional Indonesia, Jakarta',
                'kategori_id' => 2,
                'gambar' => 'https://images.unsplash.com/photo-1531058020387-3be344556be6?q=80&w=2070&auto=format&fit=crop',
            ],
            [
                'user_id' => 2,
                'judul' => 'Festival Makanan Nusantara',
                'deskripsi' => 'Cicipi berbagai hidangan lezat dan otentik dari seluruh penjuru nusantara. Dari rendang, sate, hingga jajanan pasar.',
                'tanggal_waktu' => Carbon::now()->addDays(45)->format('Y-m-d H:i:s'),
                'lokasi' => 'Taman Lapangan Banteng, Jakarta',
                'kategori_id' => 3,
                'gambar' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1974&auto=format&fit=crop',
            ],
            [
                'user_id' => 2,
                'judul' => 'Tech Startup Seminar',
                'deskripsi' => 'Pelajari cara membangun dan mendanai startup teknologi Anda langsung dari para ahli dan investor ternama.',
                'tanggal_waktu' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                'lokasi' => 'Jakarta Convention Center (JCC)',
                'kategori_id' => 4,
                'gambar' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070&auto=format&fit=crop',
            ],
            [
                'user_id' => 2,
                'judul' => 'Lari Marathon 10K',
                'deskripsi' => 'Ikuti keseruan lari marathon akhir pekan bersama ribuan pelari lainnya dan raih medali finisher eksklusif.',
                'tanggal_waktu' => Carbon::now()->addDays(20)->format('Y-m-d H:i:s'),
                'lokasi' => 'Sudirman - Thamrin, Jakarta',
                'kategori_id' => 5,
                'gambar' => 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?q=80&w=2070&auto=format&fit=crop',
            ]
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(
                ['judul' => $event['judul']], 
                $event
            );
        }
    }
}
