<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tagihans extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'bulan_tahun',
        'status_pembayaran',
        'jatuh_tempo'
    ];

    protected $table = 'tagihans';

    public function pelanggan()
    {
        return $this->belongsTo(pelanggans::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(pembayarans::class);
    }

}
