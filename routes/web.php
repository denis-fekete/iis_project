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
    return redirect('search');
});
Route::get('/search', [ConferenceController::class, 'search']);

// --------------------------------------------------------
//
// --------------------------------------------------------

Route::prefix('conferences')->group(function () {
    Route::get('/dashboard', [ConferenceController::class, 'dashboard'])
        ->middleware('auth');
    Route::get('/create', [ConferenceController::class, 'creationForm'])
        ->middleware('auth');
    Route::get('/edit/{id}', [ConferenceController::class, 'editForm'])
        ->middleware('auth');
    Route::get('/delete/{id}', [ConferenceController::class, 'delete'])
        ->middleware('auth');

    Route::post('/create', [ConferenceController::class, 'create']);
    Route::post('/edit', [ConferenceController::class, 'edit']);

    Route::prefix('conference')->group(function () {
        Route::get('/lectures/{id}', [ConferenceController::class, 'listConferenceLectures'])
            ->middleware('auth');
        Route::get('/reservations/{id}', [ConferenceController::class, 'listConferenceReservations'])
            ->middleware('auth');
        Route::get('/{id}', [ConferenceController::class, 'get']);
        Route::get('/rooms/{id}', [ConferenceController::class, 'listConferenceRooms'])
            ->middleware('auth');

        Route::post('/updateRoom', [ConferenceController::class, 'updateRoom'])
            ->middleware('auth');
        Route::post('/deleteRoom', [ConferenceController::class, 'deleteRoom'])
            ->middleware('auth');
        Route::post('/createRoom', [ConferenceController::class, 'createRoom'])
            ->middleware('auth');
        Route::post('/confirmReservation', [ConferenceController::class, 'confirmReservation'])
            ->middleware('auth');
    });
});

Route::prefix('lectures')->group(function () {
    Route::get('/dashboard', [LectureController::class, 'dashboard'])
        ->middleware('auth');
    Route::get('/create/{id}', [LectureController::class, 'createGET'])
        ->middleware('auth');
    Route::get('/edit/{id}', [LectureController::class, 'editGET'])
        ->middleware('auth');
    Route::get('/lecture/{id}', [LectureController::class, 'get']);

    Route::post('/create', [LectureController::class, 'createPOST'])
        ->middleware('auth');
    Route::post('/editSave', [LectureController::class, 'editPOST'])
        ->middleware('auth');
    Route::post('/cancel', [LectureController::class, 'cancel'])
        ->middleware('auth');
    Route::post('/confirm', [LectureController::class, 'confirm'])
        ->middleware('auth');
    Route::post('/unconfirm', [LectureController::class, 'unconfirm'])
        ->middleware('auth');
});

Route::prefix('reservations')->group(function () {
    Route::get('/reserve/{id}', [ReservationController::class, 'getForm']);
    Route::get('/dashboard', [ReservationController::class, 'getAll'])
        ->middleware('auth');
    Route::get('/cancel/{id}', [ReservationController::class, 'cancel'])
        ->middleware('auth');
    Route::get('/schedule/{id}', [ReservationController::class, 'showSchedule'])
        ->middleware('auth');

    Route::post('/saveSchedule', [ReservationController::class, 'saveSchedule'])
        ->middleware('auth');
    Route::post('/reserve', [ReservationController::class, 'create']);
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
        Route::get('/conference/rooms/{id}', [ConferenceController::class, 'listConferenceRooms'])
            ->middleware('auth');

        Route::post('/conference/reservations', [ConferenceController::class, 'editReservationsList'])
            ->middleware('auth');
    });

    Route::prefix('users')->group(function () {
        Route::get('/search', [AdminController::class, 'usersSearch'])
            ->middleware('auth');
        Route::get('/edit/{$id}', [AdminController::class, 'usersEdit'])
            ->middleware('auth');

        Route::post('/setRole', [AdminController::class, 'setRole'])
            ->middleware('auth');
    });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('auth');
});

// --------------------------------------------------------
//
// --------------------------------------------------------



Route::prefix('users')->group(function () {
    Route::get('/search/{id}', [UserController::class, 'getPerson']);
    Route::get('/profile', [UserController::class, 'profile'])
        ->middleware('auth');

    Route::post('/delete', [UserController::class, 'delete'])
        ->middleware('auth');
    Route::post('/profile/edit', [UserController::class, 'editUser'])
        ->middleware('auth');
});



// --------------------------------------------------------
//
// --------------------------------------------------------

Route::prefix('auth')->group(function () {
    Route::get('/', [AuthController::class, 'authGET'])->name('login');

    Route::post('/register', [AuthController::class, 'registration']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/logout', [AuthController::class, 'logout']);
});
