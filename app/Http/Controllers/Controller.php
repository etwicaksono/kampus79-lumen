<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function respondWithToken($token)
    {
        return response()->json([
            "json" => $token,
            "token_type" => "bearer",
            "expires_in" => null
        ], \http_response_code());
    }
}