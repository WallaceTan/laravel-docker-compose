<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "Home page";
});

Route::get('/posts/{id}', function ($i) {
    return "Post #" . $i;
});

Route::get('/info', function () {
    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_clean();
    return $phpinfo;
});

