<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bendera extends Model
{
    protected $fillable = [
        'kode',
        'nama_negara',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function kapals()
    {
        return $this->hasMany(Kapal::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
