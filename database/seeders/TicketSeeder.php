<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Tiket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan semua event
        $events = Event::all();

        foreach ($events as $event) {
            // Setiap event mendapatkan 3 tipe tiket: Reguler, VIP, VVIP
            $tickets = [
                [
                    'event_id' => $event->id,
                    'tipe' => 'reguler',
                    'harga' => rand(50, 150) * 1000,
                    'stok' => 100,
                    'deskripsi' => 'Akses area berdiri bebas (Festival).',
                ],
                [
                    'event_id' => $event->id,
                    'tipe' => 'vip',
                    'harga' => rand(200, 500) * 1000,
                    'stok' => 50,
                    'deskripsi' => 'Akses tribun duduk dengan pemandangan lebih baik.',
                ],
                [
                    'event_id' => $event->id,
                    'tipe' => 'vvip',
                    'harga' => rand(600, 1500) * 1000,
                    'stok' => 10,
                    'deskripsi' => 'Akses area eksklusif dengan meet & greet dan merchandise.',
                ]
            ];

            foreach ($tickets as $ticket) {
                Tiket::create($ticket);
            }
        }
    }
}
