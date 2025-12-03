<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared route for both user types to view projects
Route::get('/proyecto/{proyecto:no_registro}', [ProyectoController::class, 'show'])
    ->middleware(['auth:investigador,personal'])
    ->name('proyectos.show');

// Secure file download routes
Route::middleware(['auth:investigador,personal'])->group(function () {
    Route::get('/proyecto/{proyecto:no_registro}/protocolo/download', [FileDownloadController::class, 'downloadProtocolo'])
        ->name('proyectos.download-protocolo');
    Route::get('/proyecto/{proyecto:no_registro}/protocolo/view', [FileDownloadController::class, 'viewProtocolo'])
        ->name('proyectos.view-protocolo');
});

// Routes for Investigadores (researchers)
Route::middleware(['auth:investigador'])->group(function () {
    Route::get('/solicitud', [ProyectoController::class, 'create'])->name('proyectos.create');
    Route::post('/solicitud', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('/proyectos', [ProyectoController::class, 'index'])->name('user');

    // Configuration routes for investigators
    Route::get('/investigador/configuracion', [ConfigController::class, 'index'])->name('investigador.config');
    Route::put('/investigador/configuracion/perfil', [ConfigController::class, 'updateProfile'])->name('investigador.config.update-profile');
    Route::put('/investigador/configuracion/password', [ConfigController::class, 'updatePassword'])->name('investigador.config.update-password');

    // Profesor management routes
    Route::get('/api/profesores/search', [ProfesorController::class, 'search'])->name('profesores.search');
    Route::post('/api/profesores/register', [ProfesorController::class, 'register'])->name('profesores.register');
});

// Routes for Personal (administrators)
Route::middleware(['auth:personal'])->group(function () {
    Route::get('/panel', [AdminController::class, 'index'])->name('admin.dashboard');

    // Project revision and actions
    Route::get('/admin/proyectos/{proyecto}/revision', [AdminController::class, 'revision'])->name('admin.projects.revision');
    Route::post('/admin/proyectos/{proyecto}/approve', [AdminController::class, 'approve'])->name('admin.projects.approve');
    Route::post('/admin/proyectos/{proyecto}/reject', [AdminController::class, 'reject'])->name('admin.projects.reject');    // Configuration routes for administrative staff
    Route::get('/admin/configuracion', [ConfigController::class, 'index'])->name('admin.config');
    Route::put('/admin/configuracion/perfil', [ConfigController::class, 'updateProfile'])->name('admin.config.update-profile');
    Route::put('/admin/configuracion/password', [ConfigController::class, 'updatePassword'])->name('admin.config.update-password');
});
