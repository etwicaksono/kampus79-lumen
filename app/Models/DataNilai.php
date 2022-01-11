<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataNilai extends Model
{
    protected $table = "data_nilai";

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, "id_dosen", "id");
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, "nim", "nim");
    }

    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class, "id_mata_kuliah", "id");
    }
}