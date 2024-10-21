<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Adds $user to the view. This is not accessible to the user, only to
        // the blade.php templates, therefore you can see password etc... but
        // users wont see it
        View::composer('*', function($view) {
            $user = Auth::user();

            // $role = null;
            // if($user !== null) {
            //     $role = DB::table('users')->where('id', $user->id)->value('role');
            //     error_log($role);
            // }

            $view->with([
                'user' => $user,
                // 'role' => $role
            ]);
        });
    }
}
