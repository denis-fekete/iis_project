<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Cors;
use App\Http\Controllers\FormController;

Route::get('/', function () {
    return redirect('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/search', function () {
    return view('search');
});

Route::get('/profile', function () {
    return view('profile', [
            'user' => auth()->user()
        ]);
})->middleware('auth');

Route::get('/register', function () {
    return view('auth.register');
});

Route::post('/register', [AuthController::class, 'store']);


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/admin', function () {
    return view('admin.dashboard');
});
