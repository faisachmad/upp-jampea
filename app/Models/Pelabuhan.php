<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelabuhan extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }

    public function kunjungansTiba()
    {
        return $this->hasMany(Kunjungan::class, 'pelabuhan_asal_id');
    }

    public function kunjungansTolak()
    {
        return $this->hasMany(Kunjungan::class, 'pelabuhan_tujuan_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInternal($query)
    {
        return $query->whereIn('tipe', ['UPP', 'POSKER', 'WILKER']);
    }

    public function scopeExternal($query)
    {
        return $query->where('tipe', 'LUAR');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'ilike', "%{$search}%")
            ->orWhere('kode', 'ilike', "%{$search}%");
    }
}
