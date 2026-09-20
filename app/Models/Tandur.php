<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tandur extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_tani_id',
        'lahan_id',
        'tanggal_tanam',
        'status_aktif'
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
        'status_aktif' => 'boolean',
    ];

    public function kelompokTani()
    {
        return $this->belongsTo(KelompokTani::class);
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }
}
