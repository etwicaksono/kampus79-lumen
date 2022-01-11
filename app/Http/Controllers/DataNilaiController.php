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
            "nim" => "required|string|exists:mahasiswa,nim",
            "id_mata_kuliah" => "required|numeric|exists:mata_kuliah,id",
            "id_dosen" => "required|numeric|exists:dosen.id",
            "nilai" => "required|numeric",
            "keterangan" => "required|string",
        ], [
            'nim.required' => 'NIM harus diisi',
        ]);

        try {
            $user = new DataNilai;
            $user->email = $request->input("email");
            $user->role = $request->input("role");
            $user->password = \app("hash")->make($request->input("password"));
            $user->save();

            return \response()->json([
                "entity" => "user",
                "action" => "create",
                "result" => "success"
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
        //
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