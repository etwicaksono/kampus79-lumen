<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = "mahasiswa";
    protected $fillable = [
        "nim",
        "nama",
        "alamat",
        "tgl_lahir",
        "jurusan",
    ];


    public function data_nilai()
    {
        return $this->hasMany(DataNilai::class, "nim", "nim");
    }

    public function mata_kuliah()
    {
        return $this->hasMany(MataKuliah::class, "nim", "nim");
    }

    public function scopeJurusan($query, $jurusan)
    {
        return $query->whereJurusan($jurusan);
    }
}