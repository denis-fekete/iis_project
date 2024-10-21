<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Cors;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SearchController;

// --------------------------------------------------------
// Default routes accessible at all times
// --------------------------------------------------------

Route::get('/', function () {
    return redirect('home');
});

Route::get('/home', function () {
    return view('home', [
        'user' => auth()->user()
    ]);
});

Route::get('/search', [SearchController::class, 'get']);

Route::get('/conferences', [SearchController::class, 'getConference']);

// --------------------------------------------------------
// Register / login routes
// --------------------------------------------------------

Route::post('/register', [AuthController::class, 'newRegistration']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// --------------------------------------------------------
// Routes that can only be seen when user is logged in
// --------------------------------------------------------

// middleware auth makes sure that only logged in user can access this path,
// otherwise they will be routed to the route with name('login') view
Route::get('/profile', function () {
    return view('profile');

})->middleware('auth');

Route::get('/logout', [AuthController::class, 'logout']);


Route::get('/register', function () {
    return view('auth.register');
});


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('auth');
});
