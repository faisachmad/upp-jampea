<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganMuatan extends Model
{
    protected $fillable = [
        'kunjungan_id',
        'tipe',
        'jenis_barang',
        'ton_m3',
        'jenis_hewan',
        'jumlah_hewan',
    ];

    protected $casts = [
        'ton_m3' => 'decimal:2',
    ];

    // Relationships
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    // Scopes
    public function scopeBongkar($query)
    {
        return $query->where('tipe', 'BONGKAR');
    }

    public function scopeMuat($query)
    {
        return $query->where('tipe', 'MUAT');
    }
}
