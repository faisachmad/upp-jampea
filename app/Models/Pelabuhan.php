<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelabuhan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'tipe_pelabuhan_id',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($pelabuhan) {
            if (empty($pelabuhan->kode)) {
                $pelabuhan->kode = 'PLB-' . strtoupper(bin2hex(random_bytes(3)));
                
                // Ensure unique if needed, though random_bytes(3) is 16 million possibilities
                while (static::where('kode', $pelabuhan->kode)->exists()) {
                    $pelabuhan->kode = 'PLB-' . strtoupper(bin2hex(random_bytes(3)));
                }
            }
        });
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function tipePelabuhan()
    {
        return $this->belongsTo(TipePelabuhan::class, 'tipe_pelabuhan_id');
    }

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
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode', 'like', "%{$search}%");
        });
    }
}
