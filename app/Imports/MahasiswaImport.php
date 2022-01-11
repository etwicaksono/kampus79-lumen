<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;

class MahasiswaImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Mahasiswa([
            "nim" => $row[0],
            "nama" => $row[1],
            "alamat" => $row[2],
            "tgl_lahir" => $row[3],
            "jurusan" => $row[4],
            "created_at" => \date("Y-m-d H:i:s"),
            "updated_at" => \date("Y-m-d H:i:s"),
        ]);
    }
}