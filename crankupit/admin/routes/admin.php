<?php

use Illuminate\Support\Facades\Route;
use CrankUpIT\Admin\Http\Controllers\AdminSessionController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin/login', [AdminSessionController::class, 'index'])
    ->middleware('guest:admin')
    ->name('get.admin.login');
