<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }


    public function me()
    {
        return response()->json(auth()->user());
    }
}