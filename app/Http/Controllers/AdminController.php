<?php

namespace App\Http\Controllers;

use App\Enums\OrderBy;
use App\Enums\OrderDirection;
use App\Enums\RoleType;
use App\Enums\Themes;
use App\Services\AdminService;
use App\Services\ConferenceService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    /**
     * @return void Admin dashboard view
     */
    public function dashboard() {
        return view('admin.dashboard');
    }

    /**
     * Returns all cards that are available, or based on filters
     *
     * @return void search view with all conferences
     */
    public function conferencesSearch() {
        if(AdminService::amIAdmin()) {
            $themes = request()->input('themes', null);
            $orderBy = request()->input('orderBy', 'Name');
            $orderDir = request()->input('orderDir', 'asc');
            $searchString = request()->input('searchFor', null);

            $conferences = ConferenceService::getAllShortDescription($themes, $orderBy, $orderDir, $searchString);

            return view('conferences.search')
                ->with('conferences', $conferences)
                ->with('info', [
                    'themes' => Themes::cases(),
                    'directions' => OrderDirection::cases(),
                    'orders' => OrderBy::cases(),
                    'default_theme' => $themes,
                    'default_orders' => $orderBy,
                    'default_directions' => $orderDir,
                    'default_search' => $searchString,
                    'role' => auth()->user()->role,
                    ]);
        } else {
            return AdminService::invalidAccess();
        }

    }

    /**
     * Redirects admin to default search for conferences
     *
     * @return void search view with all conferences
     */
    public function conferencesSearchDefault() {
        if(AdminService::amIAdmin()) {
            return redirect('/admin/conferences/search');
        } else {
            return AdminService::invalidAccess();
        }
    }

    /**
     * Returns view with all users and allows some basic operation on them
     *
     * @return void
     */
    public function usersSearch() {
        if(AdminService::amIAdmin()) {
            $orderDir = request()->input('orderDir', 'asc');
            $searchString = request()->input('searchFor', null);

            $users = UserService::search($orderDir, $searchString);
            return view('admin.users.search')
                ->with('users', $users)
                ->with('info', [
                    'default_directions' => $orderDir,
                    'default_search' => $searchString,
                ]);
        } else {
            return AdminService::invalidAccess();
        }
    }
}
