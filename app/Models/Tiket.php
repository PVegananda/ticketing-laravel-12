<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Ditambahkan untuk fitur soft delete

class Tiket extends Model
{
    use SoftDeletes; // Menggunakan trait SoftDeletes

    protected $fillable = [
        'event_id',
        'tipe',
        'harga',
        'stok',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'detail_orders')->withPivot('jumlah', 'subtotal_harga');
    }

    
}