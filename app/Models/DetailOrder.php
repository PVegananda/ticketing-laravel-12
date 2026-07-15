<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Ditambahkan untuk fitur soft delete

class DetailOrder extends Model
{
    use SoftDeletes; // Menggunakan trait SoftDeletes

    protected $fillable = [
        'order_id',
        'tiket_id',
        'jumlah',
        'subtotal_harga',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }
}
