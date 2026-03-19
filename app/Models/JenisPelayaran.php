<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPelayaran extends Model
{
    use HasFactory;

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
