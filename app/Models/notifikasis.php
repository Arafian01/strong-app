<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notifikasis extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'pesan',
    ];

    protected $table = 'notifikasis';

    public function statusBacas() {
        return $this->hasMany(status_bacas::class);
    }
}
