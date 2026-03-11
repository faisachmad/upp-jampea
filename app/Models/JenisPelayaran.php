<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelayaran extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'prefix',
    ];

    // Relationships
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }
}
