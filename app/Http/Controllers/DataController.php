<?php

namespace App\Http\Controllers;

use App\Models\DataNilai;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Throwable;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }


    public function getAllData(Request $request)
    {
        try {
            $query = DataNilai::with(["dosen", "mahasiswa", "mata_kuliah"]);

            if ($request->has("filter_by")) {

                if ($request->has("jurusan")) {
                    $query->whereHas("mahasiswa", function ($q) use ($request) {
                        return $q->where("jurusan", $request->jurusan);
                    });
                }
                if ($request->has("id_dosen")) {
                    $query->whereHas("mahasiswa", function ($q) use ($request) {
                        return $q->where("id_dosen", $request->id_dosen);
                    });
                }
                if ($request->has("mata_kuliah")) {
                    $query->whereHas("mahasiswa", function ($q) use ($request) {
                        return $q->where("mata_kuliah", $request->mata_kuliah);
                    });
                }
            }


            $data = $query->get();

            $result = [];
            foreach ($data as $d) {
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
            }


            return \response()->json([
                "error" => false,
                "data" => $result
            ], \http_response_code());
        } catch (Throwable $t) {
            return \response()->json([
                "error" => true,
                "message" => $t->getMessage()
            ], \http_response_code());
        }
    }

    public function getMahasiswaAvg(Request $request)
    {
        try {
            $query = DataNilai::with(["dosen", "mahasiswa", "mata_kuliah"]);

            if ($request->has("filter_by")) {

                if ($request->has("jurusan")) {
                    $query->whereHas("mahasiswa", function ($q) use ($request) {
                        return $q->where("jurusan", $request->jurusan);
                    });
                }
                if ($request->has("id_dosen")) {
                    $query->whereHas("mahasiswa", function ($q) use ($request) {
                        return $q->where("id_dosen", $request->id_dosen);
                    });
                }
                if ($request->has("mata_kuliah")) {
                    $query->whereHas("mahasiswa", function ($q) use ($request) {
                        return $q->where("mata_kuliah", $request->mata_kuliah);
                    });
                }
            }


            $data = $query->get();

            $temp = [];
            foreach ($data as $d) {
                $temp[$d->nim][] = $d->nilai;
            }

            $result = [];
            foreach ($temp as $t) {
                $result[] = [
                    "nilai" => $t,
                    "avg" => \array_sum($t) / \count($t)
                ];
            }


            return \response()->json([
                "error" => false,
                "data" => $result
            ], \http_response_code());
        } catch (Throwable $t) {
            return \response()->json([
                "error" => true,
                "message" => $t->getMessage()
            ], \http_response_code());
        }
    }


    public function me()
    {
        return response()->json(auth()->user());
    }
}