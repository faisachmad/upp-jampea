<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis_kapal_id',
        'gt',
        'dwt',
        'panjang',
        'tanda_selar',
        'call_sign',
        'tempat_kedudukan',
        'bendera_id',
        'pemilik_agen',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'gt' => 'decimal:2',
        'dwt' => 'decimal:2',
        'panjang' => 'decimal:2',
    ];

    // Relationships
    public function jenisKapal()
    {
        return $this->belongsTo(JenisKapal::class);
    }

    public function bendera()
    {
        return $this->belongsTo(Bendera::class);
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }

    public function nakhodas()
    {
        return $this->hasMany(Nakhoda::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where($this->getTable().'.is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $table = $this->getTable();
            $q->where($table.'.nama', 'ilike', "%{$search}%")
                ->orWhere($table.'.call_sign', 'ilike', "%{$search}%")
                ->orWhere($table.'.pemilik_agen', 'ilike', "%{$search}%");
        });
    }
}
