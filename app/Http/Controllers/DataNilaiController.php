<?php

namespace App\Http\Controllers;

use App\Models\DataNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DataNilaiController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api, isdosen");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "nim" => "required|string|exists:mata_kuliah,nim",
            "id_mata_kuliah" => "required|numeric|exists:mata_kuliah,id",
            "id_dosen" => "required|numeric|exists:dosen,id",
            "nilai" => "required|numeric",
            "keterangan" => "required|string",
        ], [
            'nim.exists' => 'NIM not registered in mata kuliah!',
            'id_mata_kuliah.exists' => 'Wrong mata kuliah ID!',
            'id_dosen.exists' => 'Wrong dosen ID!',
        ]);

        try {
            $data_nilai = new DataNilai;
            $data_nilai->nim = $request->nim;
            $data_nilai->id_mata_kuliah = $request->id_mata_kuliah;
            $data_nilai->id_dosen = $request->id_dosen;
            $data_nilai->nilai = $request->nilai;
            $data_nilai->keterangan = $request->keterangan;
            $data_nilai->save();

            return \response()->json([
                "entity" => "data_nilai",
                "action" => "create",
                "result" => "success",
                "data" => DataNilai::latest()->first()
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "nim" => "required|string|exists:mata_kuliah,nim",
            "id_mata_kuliah" => "required|numeric|exists:mata_kuliah,id",
            "id_dosen" => "required|numeric|exists:dosen,id",
            "nilai" => "required|numeric",
            "keterangan" => "required|string",
        ], [
            'nim.exists' => 'NIM not registered in mata kuliah!',
            'id_mata_kuliah.exists' => 'Wrong mata kuliah ID!',
            'id_dosen.exists' => 'Wrong dosen ID!',
        ]);

        try {
            $data_nilai = DataNilai::find($id);
            $data_nilai->nim = $request->nim;
            $data_nilai->id_mata_kuliah = $request->id_mata_kuliah;
            $data_nilai->id_dosen = $request->id_dosen;
            $data_nilai->nilai = $request->nilai;
            $data_nilai->keterangan = $request->keterangan;
            $data_nilai->save();

            return \response()->json([
                "entity" => "data_nilai",
                "action" => "update",
                "result" => "success",
                "data" => DataNilai::find($id)
            ], \http_response_code());
        } catch (Throwable $t) {
            return \response()->json([
                "entity" => "data_nilai",
                "action" => "update",
                "result" => "failed",
                "message" => $t->getMessage()
            ], \http_response_code());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}