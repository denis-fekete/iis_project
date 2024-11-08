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

// --------------------------------------------------------
//
// --------------------------------------------------------

Route::prefix('conferences')->group(function () {
    Route::get('/search', [ConferenceController::class, 'getAll']);
    Route::get('/conference/{id}', [ConferenceController::class, 'get']);
    Route::get('/dashboard', [ConferenceController::class, 'dashboard'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::get('/create', [ConferenceController::class, 'creationForm'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::get('/edit/{id}', [ConferenceController::class, 'edit'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::get('/conference/lectures/{id}', [ConferenceController::class, 'listConferenceLectures'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::post('/conference/lectures', [ConferenceController::class, 'editLecturesList'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::get('/conference/reservations/{id}', [ConferenceController::class, 'listConferenceReservations'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::post('/conference/reservations', [ConferenceController::class, 'editReservationsList'])
        ->middleware('auth'); // protect website => user must be logged in

    Route::post('/create', [ConferenceController::class, 'create']);
});

Route::prefix('lectures')->group(function () {
    Route::get('/dashboard', [LectureController::class, 'dashboard']);
    Route::get('/create', [LectureController::class, 'createGET']);
    Route::get('/edit/{id}', [LectureController::class, 'editGET']);

    Route::post('/create', [LectureController::class, 'createPOST']);
    Route::post('/edit', [LectureController::class, 'editPOST']);
    Route::post('/cancel', [LectureController::class, 'cancel']);
});

Route::prefix('reservations')->group(function () {
    Route::get('/reserve/{id}', [ReservationController::class, 'getForm']);
    Route::get('/dashboard', [ReservationController::class, 'getAll']);
    Route::get('/cancel/{id}', [ReservationController::class, 'cancel']);
    Route::get('/schedule/{id}', [ReservationController::class, 'showSchedule']);

    Route::post('/reserve', [ReservationController::class, 'create']);
});


Route::get('/person/{id}', [SearchController::class, 'getPerson']);




// --------------------------------------------------------
//
// --------------------------------------------------------

Route::post('/register', [AuthController::class, 'newRegistration']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// --------------------------------------------------------
//
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
