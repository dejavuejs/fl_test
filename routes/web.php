<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ForecastController;

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

Route::get('city', [CityController::class, 'index'])->defaults(
    'config',
    [
        'view' => 'index'
    ]
)->name('city.index');

Route::get('city/all', [CityController::class, 'getAll'])->name('city.all');

Route::post('city/store', [CityController::class, 'store'])->defaults(
    'config',
    [
        'redirect' => 'city.create'
    ]
)->name('city.store');

Route::post('city/delete/{id}', [CityController::class, 'destroy'])->defaults(
    'config',
    [
        'redirect' => 'city.index'
    ]
);

Route::post('city/forecast', [ForecastController::class, 'fetch'])->name('city.forecast');

// Route::post('city/forecast', [ForecastController::class, 'fetchAll']);
