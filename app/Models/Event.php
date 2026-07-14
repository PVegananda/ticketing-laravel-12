<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'lokasi',
        'gambar',
        'tanggal_waktu',
    ];

    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getStatusAttribute()
    {
        $eventTime = $this->tanggal_waktu;
        $now = now();

        if ($eventTime->isFuture()) {
            return 'Upcoming';
        }

        if ($eventTime->copy()->addHours(3)->isFuture()) {
            return 'Ongoing';
        }

        return 'Completed';
    }

    public function hasSales()
    {
        return $this->orders()->exists();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_waktu', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query
            ->where('tanggal_waktu', '<=', now())
            ->where('tanggal_waktu', '>=', now()->copy()->subHours(3));
    }

    public function scopeCompleted($query)
    {
        return $query->where('tanggal_waktu', '<', now()->copy()->subHours(3));
    }

    public function getImageUrlAttribute()
{
    if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
        return $this->gambar;
    }

    if ($this->gambar && Storage::disk('public')->exists($this->gambar)) {
        return Storage::url($this->gambar);
    }

    return asset('images/konser.jpg');
}
}

