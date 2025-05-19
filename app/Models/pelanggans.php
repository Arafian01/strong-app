<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelanggans extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paket_id',
        'alamat',
        'telepon',
        'status',
        'tanggal_aktif',
        'tanggal_langganan',

    ];

    protected $table = 'pelanggans';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(pakets::class);
    }

    public function tagihan()
    {
        return $this->hasMany(tagihans::class);
    }
}
