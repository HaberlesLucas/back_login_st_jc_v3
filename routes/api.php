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

    //restablecer contraseña
    Route::POST('/reset', [UserController::class, 'resetPassword']);
    Route::get('/reset-password/{token}', [UserController::class, 'showResetForm']);
    Route::POST('/reset-password', [UserController::class, 'updatePasswordReset']);

    // Rutas protegidas con autenticación
    Route::middleware('auth:api')->group(function () {
        Route::get('/get-user-info', [UserController::class, 'getLoggedInUser']); // Ruta protegida
        Route::POST('/register', [AuthController::class, 'setUsuario']);

        Route::POST('/logout', [AuthController::class, 'logout']);
        //Route::POST('/refresh', [AuthController::class, 'refresh']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');
        
        Route::PUT('/update-user-name', [UserController::class, 'updateName']);
        Route::PUT('/update-user-password', [UserController::class, 'updatePassword']);

        // CRUD de usuarios
        Route::get('/', [UserController::class, 'getUsuarios']);
        Route::get('/{dni}', [UserController::class, 'getUsuario']);
        Route::PUT('/restore/{dni}', [UserController::class, 'restoreUsuario']);
        Route::DELETE('/{dni}', [UserController::class, 'deleteUsuario']);
        Route::PUT('/{dni}', [UserController::class, 'updateUsuario']);
        Route::get('/check-dni/{dni}', [UserController::class, 'checkDniExists']);
        Route::get('/check-correo/{correo}', [UserController::class, 'checkCorreoExists']);
    });
});
