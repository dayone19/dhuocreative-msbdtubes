<?php
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgotPass', function () {
    return view('auth.forgotPass');
})->name('password.request');

Route::get('/tentor/dashboard', function () {
    return view('tentor.dashboard');
})->name('dashboard');