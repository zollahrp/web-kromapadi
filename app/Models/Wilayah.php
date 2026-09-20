<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $fillable = ['name', 'provinsi', 'kota', 'kecamatan', 'kelurahan'];

    public function masterAdmins()
    {
        return $this->hasMany(User::class, 'wilayah_id');
    }

    public function kelompokTanis()
    {
        return $this->hasMany(KelompokTani::class);
    }
}
