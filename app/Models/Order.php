<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Ditambahkan untuk fitur soft delete

class Order extends Model
{
    use SoftDeletes; // Menggunakan trait SoftDeletes

    protected $fillable = [
        'user_id',
        'event_id',
        'order_date',
        'total_harga',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tikets()
    {
        return $this->belongsToMany(Tiket::class, 'detail_orders')->withPivot('jumlah', 'subtotal_harga');
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
    
}
