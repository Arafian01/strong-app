<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status_baca extends Model
{
    protected $fillable = [
        'notifikasi_id',
        'user_id'        
    ];

    protected $table = 'status_bacas';

    public function notifikasi() {
        return $this->belongsTo(Notifikasi::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
