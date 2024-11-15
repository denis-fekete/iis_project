<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConferenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Cors;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SearchController;

// --------------------------------------------------------
//
// --------------------------------------------------------

Route::get('/', function () {
    return redirect('home');
});

Route::get('/home', function () {
    return view('home', [
        'user' => auth()->user()
    ]);
});

Route::get('/profile', function () {
    return view('profile');

})->middleware('auth');

// --------------------------------------------------------
//
// --------------------------------------------------------

Route::prefix('conferences')->group(function () {
    Route::get('/search/{themes};{orderBy};{orderDir}', [ConferenceController::class, 'getAll']);
    Route::get('/conference/{id}', [ConferenceController::class, 'get']);
    Route::get('/dashboard', [ConferenceController::class, 'dashboard'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::get('/create', [ConferenceController::class, 'creationForm'])
        ->middleware('auth');

    Route::get('/edit/{id}', [ConferenceController::class, 'editForm'])
        ->middleware('auth');

    Route::get('/conference/lectures/{id}', [ConferenceController::class, 'listConferenceLectures'])
        ->middleware('auth');

    Route::get('/conference/reservations/{id}', [ConferenceController::class, 'listConferenceReservations'])
        ->middleware('auth');

    Route::post('/conference/reservations', [ConferenceController::class, 'editReservationsList'])
        ->middleware('auth');

    Route::post('/create', [ConferenceController::class, 'create']);
    Route::post('/edit', [ConferenceController::class, 'edit']);
});

Route::prefix('lectures')->group(function () {
    Route::get('/dashboard', [LectureController::class, 'dashboard']);
    Route::get('/create/{id}', [LectureController::class, 'createGET']);
    Route::get('/edit/{id}', [LectureController::class, 'editGET']);
    Route::get('/lecture/{id}', [LectureController::class, 'get']);

    Route::post('/create', [LectureController::class, 'createPOST']);
    Route::post('/editSave', [LectureController::class, 'editPOST']);
    Route::post('/cancel', [LectureController::class, 'cancel']);

    Route::post('/confirm', [LectureController::class, 'confirm']);
    Route::post('/unconfirm', [LectureController::class, 'unconfirm']);
});

Route::prefix('reservations')->group(function () {
    Route::get('/reserve/{id}', [ReservationController::class, 'getForm']);
    Route::get('/dashboard', [ReservationController::class, 'getAll']);
    Route::get('/cancel/{id}', [ReservationController::class, 'cancel']);
    Route::get('/schedule/{id}', [ReservationController::class, 'showSchedule']);

    Route::post('/reserve', [ReservationController::class, 'create'])
        ->middleware('auth');
});

Route::get('/person/{id}', [SearchController::class, 'getPerson']);


// --------------------------------------------------------
//
// --------------------------------------------------------

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'registration']);
    Route::get('/register', function () {
        return view('auth.auth');
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/login', function () {
        return view('auth.auth');
    })->name('login');

    Route::get('/logout', [AuthController::class, 'logout']);
});


// --------------------------------------------------------
//
// --------------------------------------------------------

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('auth');
});
