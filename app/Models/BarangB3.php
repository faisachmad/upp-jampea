<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangB3 extends Model
{
    protected $fillable = [
        'nama',
        'un_number',
        'kelas',
        'kategori',
    ];

    // Relationships
    public function kunjunganB3s()
    {
        return $this->hasMany(KunjunganB3::class);
    }
}
