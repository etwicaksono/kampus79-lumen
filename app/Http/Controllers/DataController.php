<?php

namespace App\Http\Controllers;

use App\Models\DataNilai;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }


    public function getAllData()
    {
        $data = DataNilai::with(["dosen", "mahasiswa", "mata_kuliah"])->get();


        /*  $data = DB::table('data_nilai')
            ->rightJoin("mahasiswa", "mahasiswa.nim", "=", "data_nilai.nim")
            ->leftJoin("dosen", "dosen.id", "=", "data_nilai.dosen_id")
            ->leftJoin("mata_kuliah", "mata_kuliah.id", "=", "data_nilai.mata_kuliah.id")
            ->groupBy("mahasiswa.nim")
            ->select(["mahasiswa.*", "dosen.nama"])
            ->get(); */

        $result = [];
        $container = [];
        foreach ($data as $d) {
            if (!in_array($d->nim, $container)) {
                $d1 = new DateTime($d->mahasiswa->tgl_lahir);
                $d2 = new DateTime(date("Y-m-d"));
                $diff = $d2->diff($d1);

                $result[] = [
                    "nim" => $d->nim,
                    "nama" => $d->mahasiswa->nama,
                    "jurusan" => $d->mahasiswa->jurusan,
                    "umur" => $diff->y,
                    "dosen" => $d->dosen->nama,
                    "nm_mata_kuliah" => $d->mata_kuliah->nama,
                    "nilai" => $d->nilai,
                ];
                $container[] = $d->nim;
            }
        }


        return \response()->json(["data" => $result]);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }
}