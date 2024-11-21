<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConferenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Cors;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RoomController;
use App\Services\RoomService;

// --------------------------------------------------------
//
// --------------------------------------------------------

Route::get('/', function () {
    return redirect('home');
});

Route::get('/home', function () {
    return view('home', ['user' => auth()->user()])
        ->with('notification', [
            'test notification',
            'second notification test',
            ]);
});

Route::get('/home1', function () {
    return redirect('/home')
        ->withErrors(['test' => 'Test error message']);
});

// --------------------------------------------------------
//
// --------------------------------------------------------

Route::prefix('conferences')->group(function () {
    // Route::get('/search/{themes};{orderBy};{orderDir};{searchString?}', [ConferenceController::class, 'search']);
    Route::get('/search', [ConferenceController::class, 'search']);
    Route::get('/conference/{id}', [ConferenceController::class, 'get']);
    Route::get('/dashboard', [ConferenceController::class, 'dashboard'])
        ->middleware('auth'); // protect website => user must be logged in
    Route::get('/create', [ConferenceController::class, 'creationForm'])
        ->middleware('auth');
    Route::get('/edit/{id}', [ConferenceController::class, 'editForm'])
        ->middleware('auth');
    Route::get('/delete/{id}', [ConferenceController::class, 'delete'])
        ->middleware('auth');
    Route::get('/conference/lectures/{id}', [ConferenceController::class, 'listConferenceLectures'])
        ->middleware('auth');
    Route::get('/conference/reservations/{id}', [ConferenceController::class, 'listConferenceReservations'])
        ->middleware('auth');

    Route::get('/conference/rooms/{id}', [ConferenceController::class, 'listConferenceRooms'])
        ->middleware('auth');
    Route::post('/conference/updateRoom', [ConferenceController::class, 'updateRoom'])
        ->middleware('auth');
    Route::post('/conference/deleteRoom', [ConferenceController::class, 'deleteRoom'])
        ->middleware('auth');
    Route::post('/conference/createRoom', [ConferenceController::class, 'createRoom'])
        ->middleware('auth');

    Route::post('/conference/reservations', [ConferenceController::class, 'editReservationsList'])
        ->middleware('auth');
    Route::post('/create', [ConferenceController::class, 'create']);
    Route::post('/edit', [ConferenceController::class, 'edit']);
});

Route::prefix('lectures')->group(function () {
    Route::get('/dashboard', [LectureController::class, 'dashboard'])
        ->middleware('auth');
    Route::get('/create/{id}', [LectureController::class, 'createGET'])
        ->middleware('auth');
    Route::get('/edit/{id}', [LectureController::class, 'editGET'])
        ->middleware('auth');
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
    Route::post('/saveSchedule', [ReservationController::class, 'saveSchedule']);

    Route::post('/reserve', [ReservationController::class, 'create'])
        ->middleware('auth');
});

// --------------------------------------------------------
//
// --------------------------------------------------------



Route::prefix('users')->group(function () {
    Route::get('/search/{id}', [UserController::class, 'getPerson']);
    Route::get('/profile', [UserController::class, 'profile'])
        ->middleware('auth');
    Route::post('/profile/edit', [UserController::class, 'editUser'])
        ->middleware('auth');
    Route::get('/delete/{id}', [UserController::class, 'delete'])
        ->middleware('auth');
});



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
    Route::prefix('conferences')->group(function () {
        Route::get('/search', [AdminController::class, 'conferencesSearch'])
            ->middleware('auth');
        Route::get('/edit/{id}', [ConferenceController::class, 'editForm'])
            ->middleware('auth');
        Route::get('/conference/lectures/{id}', [ConferenceController::class, 'listConferenceLectures'])
            ->middleware('auth');
        Route::get('/conference/reservations/{id}', [ConferenceController::class, 'listConferenceReservations'])
            ->middleware('auth');
        Route::post('/conference/reservations', [ConferenceController::class, 'editReservationsList'])
            ->middleware('auth');
        Route::get('/conference/rooms/{id}', [ConferenceController::class, 'listConferenceRooms'])
            ->middleware('auth');
    });

    Route::prefix('users')->group(function () {
        Route::get('/search', [AdminController::class, 'usersSearch'])
            ->middleware('auth');
        Route::get('/edit/{$id}', [AdminController::class, 'usersEdit'])
            ->middleware('auth');
    });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('auth');
});
