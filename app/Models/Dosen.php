<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = "dosen";

    public function data_nilai()
    {
        return $this->hasMany(DataNilai::class, "id_dosen", "id");
    }
}