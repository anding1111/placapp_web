<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlateController;
use App\Http\Controllers\OnlineUserController;


// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

// Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Buscar placas
    Route::post('/list-of-plate', [PlateController::class, 'search'])->name('plate.search');
    
    // Usuarios en línea
    Route::get('/online-users', [OnlineUserController::class, 'index'])->name('online.users');
    Route::post('/online', [OnlineUserController::class, 'updateStatus'])->name('online.update');
    Route::post('/fetch-users-online', [OnlineUserController::class, 'fetchOnlineUsers'])->name('online.fetch');
    
    // Rutas solo para administradores
    Route::middleware(['admin'])->group(function () {
        // Administración de usuarios
        Route::resource('users', UserController::class);
        
        // Importar datos
        Route::get('/upload', [PlateController::class, 'showUploadForm'])->name('upload.form');
        Route::post('/upload-excel', [PlateController::class, 'uploadExcel'])->name('upload.excel');
        
        // Borrar datos
        Route::get('/delete', [PlateController::class, 'showDeleteForm'])->name('delete.form');
        Route::post('/delete-excel', [PlateController::class, 'deleteExcel'])->name('delete.excel');
        Route::post('/null-plate', [PlateController::class, 'nullPlate'])->name('plate.null');
        Route::post('/null-user', [UserController::class, 'nullUser'])->name('user.null');
    });
});