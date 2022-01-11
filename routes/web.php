<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    $router->get("me", "DataController@me");
    $router->get("get-all", "DataController@getAllData");
    $router->get("mahasiswa-avg", "DataController@getMahasiswaAvg");
    $router->get("jurusan-avg", "DataController@getJurusanAvg");
});

$router->group(["middleware" => ["auth", "isdosen"], "prefix" => "api"], function ($router) {
    $router->post("data-nilai", "DataNilaiController@store");
    $router->put("data-nilai/{id}/update", "DataNilaiController@update");
    $router->delete("data-nilai/{id}/delete", "DataNilaiController@destroy");
});

$router->group(["prefix" => "api"], function () use ($router) {
    $router->post("register", "AuthController@register");
    $router->post("login", "AuthController@login");
});