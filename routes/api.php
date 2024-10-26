<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




Route::group([
    'middleware' => 'api',
    'prefix' => 'v3',
], function () {
    // Rutas públicas
    Route::POST('/login', [AuthController::class, 'login']);

    // Rutas protegidas con autenticación
    Route::middleware('auth:api')->group(function () {
        Route::POST('/register', [AuthController::class, 'setUsuario']); // Ruta protegida para admins
        Route::get('/get-user-info', [UserController::class, 'getLoggedInUser']); // Ruta protegida

        Route::POST('/logout', [AuthController::class, 'logout']);
        Route::POST('/refresh', [AuthController::class, 'refresh']);



            // update-user-name
            // update-user-password
        Route::PUT('/update-user-name', [UserController::class, 'updateName']);
        Route::PUT('/update-user-password', [UserController::class, 'updatePassword']);

        // CRUD de usuarios
        Route::get('/', [UserController::class, 'getUsuarios']);
        Route::get('/{dni}', [UserController::class, 'getUsuario']);
        Route::PUT('/restore/{dni}', [UserController::class, 'restoreUsuario']);
        Route::DELETE('/{dni}', [UserController::class, 'deleteUsuario']);
        Route::PUT('/{dni}', [UserController::class, 'updateUsuario']);
        Route::get('/check-dni/{dni}', [UserController::class, 'checkDniExists']);
    });
});








// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'v3',
// ], function () {
//     Route::POST('/login', [AuthController::class, 'login']);
    
//     Route::get('/get-user-info', [UserController::class, 'getLoggedInUser'])->middleware('auth:api');

//     Route::POST('/register', [AuthController::class, 'setUsuario']);
//     Route::get('/', [UserController::class, 'getUsuarios']);
//     Route::get('/{dni}', [UserController::class, 'getUsuario']);
//     Route::PUT('/restore/{dni}', [UserController::class, 'restoreUsuario']);
//     Route::DELETE('/{dni}', [UserController::class, 'deleteUsuario']);
//     Route::PUT('/{dni}', [UserController::class, 'updateUsuario']);
//     Route::get('/check-dni/{dni}', [UserController::class, 'checkDniExists']);


//     Route::POST('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
//     Route::POST('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');

// });






// Route::middleware('auth:api')->get('/get-user-info', [UserController::class, 'getLoggedInUser']);

// Route::get('/get-user-info', function () {
//     echo ("hola");
// });