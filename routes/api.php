<?php

use App\Http\Controllers\Api\ProdukController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/produkApi', [ProdukController::class, 'index']);
Route::get('produkApi/{id}', [ProdukController::class, 'DetailApi']);
