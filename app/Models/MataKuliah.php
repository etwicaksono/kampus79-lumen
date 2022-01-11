<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = "mata_kuliah";

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, "nim", "nim");
    }
}