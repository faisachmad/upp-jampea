<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganB3 extends Model
{
    protected $table = 'kunjungan_b3s';
    protected $fillable = [
        'kunjungan_id',
        'barang_b3_id',
        'jenis_kegiatan',
        'bentuk_muatan',
        'jumlah_ton',
        'jumlah_container',
        'kemasan',
        'jumlah',
        'petugas',
    ];

    protected $casts = [
        'jumlah_ton' => 'decimal:2',
    ];

    // Relationships
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function barangB3()
    {
        return $this->belongsTo(BarangB3::class);
    }
}
