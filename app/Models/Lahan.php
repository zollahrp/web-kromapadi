<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    protected $fillable = ['name', 'kelompok_tani_id', 'klasifikasi', 'uploaded_by'];

    public function kelompokTani()
    {
        return $this->belongsTo(KelompokTani::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function riwayatScans()
    {
        return $this->hasMany(RiwayatScan::class);
    }
}
