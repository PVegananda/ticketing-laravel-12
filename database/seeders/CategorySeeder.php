<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Konser Musik',
            'Pameran Seni',
            'Festival Makanan',
            'Seminar & Workshop',
            'Olahraga',
            'Teater & Drama'
        ];

        foreach ($categories as $category) {
            Kategori::firstOrCreate([
                'nama' => $category,
                'slug' => Str::slug($category)
            ]);
        }
    }
}
