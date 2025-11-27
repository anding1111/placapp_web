<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlateController;
use App\Http\Controllers\OnlineUserController;


// Rutas de autenticación (excluidas de CSRF)
Route::middleware(['guest', 'web'])->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    // Rutas de autorización de dispositivos
    Route::get('/device-auth', [AuthController::class, 'showDeviceAuth'])->name('device.auth');
    Route::post('/device-authorize', [AuthController::class, 'authorizeDevice'])->name('device.authorize');
});

// Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Buscar placas
    Route::post('/list-of-plate', [PlateController::class, 'searchPlates'])->name('plate.search');
    
    // Usuarios en línea
    Route::get('/online-users', [OnlineUserController::class, 'index'])->name('online.users');
    Route::post('/online', [OnlineUserController::class, 'updateStatus'])->name('online.update');
    Route::post('/fetch-users-online', [OnlineUserController::class, 'fetchOnlineUsers'])->name('online.fetch');

    // Rutas solo para administradores
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {

        // Administración de usuarios
        Route::resource('users', UserController::class);
        
        Route::get('/create', [UserController::class, 'create'])->name('users.create');

        // Importar datos
        Route::get('/upload', [PlateController::class, 'showUploadForm'])->name('upload.form');
        Route::post('/upload-excel', [PlateController::class, 'uploadExcel'])->name('upload.excel');
        
        // Borrar datos
        Route::get('/delete', [PlateController::class, 'showDeleteForm'])->name('delete.form');
        Route::post('/delete-excel', [PlateController::class, 'deleteExcel'])->name('delete.excel');

        // API para DataTables y manipulación de datos
        Route::post('/fetch-plates', [PlateController::class, 'fetchDataTable'])->name('plate.datatable');
        Route::post('/fetch-plate', [PlateController::class, 'fetchPlate'])->name('plate.fetch');
        Route::post('/null-plate', [PlateController::class, 'nullPlate'])->name('plate.null');
        
        // API para DataTables de usuarios
        Route::post('/fetch-users', [UserController::class, 'getUsersData'])->name('user.datatable');
        Route::post('/fetch-user', [UserController::class, 'getUserDetails'])->name('user.fetch');
        Route::post('/null-user', [UserController::class, 'nullUser'])->name('user.null');
    });
});