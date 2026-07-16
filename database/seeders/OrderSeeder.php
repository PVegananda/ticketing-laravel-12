<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        
        if($users->isEmpty()) {
            return;
        }

        // Get 3 random tickets
        $tikets = Tiket::with('event')->inRandomOrder()->limit(5)->get();

        foreach ($tikets as $index => $tiket) {
            $user = $users->random();
            $jumlah = rand(1, 3);
            $subtotal = $tiket->harga * $jumlah;

            $order = Order::create([
                'user_id' => $user->id,
                'event_id' => $tiket->event_id,
                'order_date' => Carbon::now()->subDays(rand(1, 10)),
                'total_harga' => $subtotal,
                'status' => rand(0, 1) ? 'paid' : 'pending',
            ]);

            DetailOrder::create([
                'order_id' => $order->id,
                'tiket_id' => $tiket->id,
                'jumlah' => $jumlah,
                'subtotal_harga' => $subtotal,
            ]);
            
            if ($tiket->stok !== null) {
                $tiket->decrement('stok', $jumlah);
            }
        }
    }
}
