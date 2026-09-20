<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokTani extends Model
{
    protected $fillable = ['name', 'wilayah_id', 'master_admin_id', 'nama_ketua', 'lokasi_long', 'lokasi_lat', 'alamat'];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function masterAdmin()
    {
        return $this->belongsTo(User::class, 'master_admin_id');
    }

    public function lahans()
    {
        return $this->hasMany(Lahan::class);
    }
}
