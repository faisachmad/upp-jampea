<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Kunjungan extends Model
{
    use HasFactory;

    public function getRouteKey()
    {
        return Crypt::encryptString($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $id = Crypt::decryptString($value);

            return $this->where($field ?? $this->getRouteKeyName(), $id)->firstOrFail();
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    protected $fillable = [
        'pelabuhan_id',
        'kapal_id',
        'jenis_pelayaran_id',
        'nakhoda_id',
        'bulan',
        'tahun',
        'tgl_tiba',
        'jam_tiba',
        'pelabuhan_asal_id',
        'status_muatan_tiba',
        'tgl_tambat',
        'jam_tambat',
        'tgl_berangkat',
        'jam_berangkat',
        'pelabuhan_tujuan_id',
        'status_muatan_tolak',
        'no_spb_tiba',
        'no_spb_tolak',
        'eta',
        'pnp_datang_dewasa',
        'pnp_datang_anak',
        'pnp_tolak_dewasa',
        'pnp_tolak_anak',
        'penumpang_turun',
        'penumpang_naik',
        'kend_datang_gol1',
        'kend_datang_gol2',
        'kend_datang_gol3',
        'kend_datang_gol4a',
        'kend_datang_gol4b',
        'kend_datang_gol5',
        'kend_tolak_gol1',
        'kend_tolak_gol2',
        'kend_tolak_gol3',
        'kend_tolak_gol4a',
        'kend_tolak_gol4b',
        'kend_tolak_gol5',
        'mobil_turun',
        'mobil_naik',
        'motor_turun',
        'motor_naik',
        'lanjutan_jenis',
        'lanjutan_ton',
        'lanjutan_mobil',
        'lanjutan_motor',
        'lanjutan_penumpang',
    ];

    protected $casts = [
        'tgl_tiba' => 'date',
        'tgl_tambat' => 'date',
        'tgl_berangkat' => 'date',
        'eta' => 'date',
        'lanjutan_ton' => 'decimal:2',
        'penumpang_turun' => 'integer',
        'penumpang_naik' => 'integer',
    ];

    // Relationships
    public function pelabuhan()
    {
        return $this->belongsTo(Pelabuhan::class);
    }

    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }

    public function jenisPelayaran()
    {
        return $this->belongsTo(JenisPelayaran::class);
    }

    public function nakhoda()
    {
        return $this->belongsTo(Nakhoda::class);
    }

    public function pelabuhanAsal()
    {
        return $this->belongsTo(Pelabuhan::class, 'pelabuhan_asal_id');
    }

    public function pelabuhanTujuan()
    {
        return $this->belongsTo(Pelabuhan::class, 'pelabuhan_tujuan_id');
    }

    public function muatans()
    {
        return $this->hasMany(KunjunganMuatan::class);
    }

    public function b3s()
    {
        return $this->hasMany(KunjunganB3::class);
    }

    // Scopes
    public function scopeByPeriode($query, $tahun, $bulan = null)
    {
        $query->where('tahun', $tahun);
        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        return $query;
    }

    public function scopeByPelabuhan($query, $pelabuhanId)
    {
        return $query->where('pelabuhan_id', $pelabuhanId);
    }

    public function scopeByJenisPelayaran($query, $jenisPelayaranId)
    {
        return $query->where('jenis_pelayaran_id', $jenisPelayaranId);
    }
}
