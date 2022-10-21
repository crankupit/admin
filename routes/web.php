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
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:admin', 'web',
])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.admin-dashboard');
    })->name('admin.dashboard');
});

Route::middleware([
    'auth:admin', 'web',
])->group(function () {
    Route::get('/admin/profile', function () {
        return view('admin.profile.show');
    })->name('admin.profile.show');
});
