<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipePelabuhan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function pelabuhans()
    {
        return $this->hasMany(Pelabuhan::class, 'tipe_pelabuhan_id');
    }
}
