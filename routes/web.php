<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Cors;
use App\Http\Controllers\FormController;

Route::get('/', function () {
    return view('post_sender');
});

Route::get('/form', [FormController::class, 'create'])->name('form.create');

Route::get('/form/submit', [FormController::class, 'submit'])->name('form.submit');
