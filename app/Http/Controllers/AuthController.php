<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api", ["except" => ["login", "register"]]);
    }

    public function register(Request $request)
    {
        // Validate incoming request
        $this->validate($request, [
            "email" => "required|string|unique:user",
            "role" => "required",
            "password" => "required|confirmed",
        ]);

        try {
            $user = new User;
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

    public function login(Request $request)
    {
        $this->validate($request, [
            "email" => "required|string",
            "password" => "required|string"
        ]);

        $credential = $request->only(["email", "password"]);

        if (!$token = Auth::attempt($credential)) {
            return \response()->json(["message" => "Unauthorized"], \http_response_code());
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}