<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nakhoda extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kapal_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'ilike', "%{$search}%");
    }
}
