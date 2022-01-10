<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version() . " | " . date("d-m-Y H:i:s");
});

$router->get('/key', function () {
    if (!app()->environment("prod")) return \Illuminate\Support\Str::random(32);
});

$router->group(["middleware" => "auth", "prefix" => "api"], function ($router) {
    $router->get("me", [AuthController::class, "me"]);
});

$router->group(["prefix" => "api"], function () use ($router) {
    $router->post("register", [AuthController::class, "register"]);
    $router->post("login", [AuthController::class, "login"]);
});