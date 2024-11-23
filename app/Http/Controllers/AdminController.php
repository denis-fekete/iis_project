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
     * Returns view with all users and allows some basic operation on them
     *
     * @return void
     */
    public function usersSearch() {
        if(AdminService::amIAdmin()) {
            $orderDir = request()->input('order', 'asc');
            $searchString = request()->input('search', null);

            $users = UserService::search($orderDir, $searchString);
            return view('admin.users.search')
                ->with('users', $users)
                ->with('info', [
                    'directions' => OrderDirection::cases(),
                    'default_directions' => $orderDir,
                    'default_search' => $searchString,
                    'roles' => RoleType::cases(),
                ]);
        } else {
            return AdminService::invalidAccess();
        }
    }

    /**
     * Changes user role
     *
     * @return void
     */
    public function setRole(Request $request) {
        if(AdminService::amIAdmin()) {
            $userId = $request->input('user_id', null);
            $role = $request->input('role', null);
            error_log($userId);
            error_log($role);
            $res = AdminService::setRole($userId, $role);
            error_log($res);

            return redirect()->back()
                ->with('notification', [$res]);
        } else {
            return AdminService::invalidAccess();
        }
    }
}
