<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pakets extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_paket',
        'harga',
        'deskripsi',
    ];

    protected $table = 'pakets';

    public function pelanggan()
    {
        return $this->hasMany(pelanggans::class);
    }
}
