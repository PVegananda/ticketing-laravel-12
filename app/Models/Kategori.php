<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Ditambahkan untuk fitur soft delete
use PhpParser\Node\Expr\FuncCall;

class Kategori extends Model
{
    use SoftDeletes; // Menggunakan trait SoftDeletes

    protected $fillable = [
        'nama',
    ];
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
