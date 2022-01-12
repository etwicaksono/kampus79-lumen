<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaImport;
use App\Models\DataNilai;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use Illuminate\Support\Arr;
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
            $query = DataNilai::with("dosen", "mahasiswa", "mata_kuliah");

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
            foreach ($temp as $key => $t) {
                $result[] = [
                    "nim" => $key,
                    "avg" => \array_sum($t) / \count($t),
                    "nilai" => $t,
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

    public function getJurusanAvg(Request $request)
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
                $temp[$d->mahasiswa->jurusan][] = $d->nilai;
            }

            $result = [];
            foreach ($temp as $key => $t) {
                $result[] = [
                    "jurusan" => $key,
                    "avg" => \array_sum($t) / \count($t),
                    "nilai" => $t,
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

    public function imporExcel(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        try {

            // menangkap file excel
            $file = $request->file('file');

            // membuat nama file unik
            $nama_file = rand() . $file->getClientOriginalName();

            // upload ke folder excel di dalam folder public
            $file->move('excel', $nama_file);

            // import data
            Excel::import(new MahasiswaImport, public_path('/excel/' . $nama_file));

            return \response()->json([
                "entity" => "mahasiswa",
                "action" => "import",
                "result" => "success",
                "data" => Mahasiswa::latest()->take(10)->get()
            ], \http_response_code());
        } catch (Throwable $t) {
            return \response()->json([
                "entity" => "user",
                "action" => "create",
                "result" => "failed",
                "message" => $t->getMessage()
            ], \http_response_code());
        }
    }

    public function imporExcelForUpdate(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        try {

            // menangkap file excel
            $file = $request->file('file');

            // membuat nama file unik
            $nama_file = rand() . $file->getClientOriginalName();

            // upload ke folder excel di dalam folder public
            $file->move('excel', $nama_file);

            // import data
            $data = Excel::toArray(new MahasiswaImport, public_path('/excel/' . $nama_file));

            /* $result = \array_map(function (array $array): array {
                return \array_map(function (array $arr): array {
                    return [
                        "nim" => $arr[0],
                        "nama" => $arr[1],
                        "alamat" => $arr[2],
                        "tgl_lahir" => $arr[3],
                        "jurusan" => $arr[4],
                        "created_at" => \date("Y-m-d H:i:s"),
                        "updated_at" => \date("Y-m-d H:i:s"),
                    ];
                }, $array);
            }, $data); */

            $result = [];
            foreach ($data[0] as $d) {
                $result[] = [
                    "nim" => $d[0],
                    "nama" => $d[1],
                    "alamat" => $d[2],
                    "tgl_lahir" => $d[3],
                    "jurusan" => $d[4],
                    "created_at" => \date("Y-m-d H:i:s"),
                    "updated_at" => \date("Y-m-d H:i:s"),
                ];
            }

            Mahasiswa::massUpdate(values: $result, uniqueBy: "nim");

            return \response()->json([
                "entity" => "mahasiswa",
                "action" => "update",
                "result" => "success",
                "data" => Mahasiswa::latest()->take(10)->get(),
                // "data" => $result
            ], \http_response_code());
        } catch (Throwable $t) {
            return \response()->json([
                "entity" => "user",
                "action" => "update",
                "result" => "failed",
                "message" => $t->getMessage()
            ], \http_response_code());
        }
    }


    public function me()
    {
        return response()->json(auth()->user());
    }
}