<?php

use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\ParquesController;
use App\Http\Controllers\TipoVisitanteController;
use App\Http\Controllers\UsuarioController;
use App\Models\Parques;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('tipos-visitante', TipoVisitanteController::class);
Route::apiResource('usuarios', UsuarioController::class);
/* Route::apiResource('parques', ParquesController::class);
Route::apiResource('estadisticas', EstadisticasController::class); */
Route::get('estadisticas/obtener', [EstadisticasController::class, 'obtener']);
Route::get('parques/obtener', [ParquesController::class, 'obtener']);
Route::post('usuario/login', [UsuarioController::class, 'findByUsernameAndPassword']);
/* Route::post('estadisticas/obtener', [EstadisticasController::class, 'obtener']); */
