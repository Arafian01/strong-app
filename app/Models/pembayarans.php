<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembayarans extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagihan_id',
        'image',
        'tanggal_kirim',
        'user_id',
        'status_verifikasi',
        'tanggal_verifikasi'
    ];

    protected $table = 'pembayarans';

    public function tagihan()
    {
        return $this->belongsTo(tagihans::class, 'tagihan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
