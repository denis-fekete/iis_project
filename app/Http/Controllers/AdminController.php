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
     * @return view search view with all conferences
     */
    public function conferencesSearch() {
        if(AdminService::amIAdmin()) {
            $themes = request()->input('themes', null);
            $orderBy = request()->input('order', 'Name');
            $orderDir = request()->input('directions', 'asc');
            $searchString = request()->input('search', null);

            $conferences = ConferenceService::getAllShortDescription($themes, $orderBy, $orderDir, $searchString);

            return view('conferences.search')
                ->with('conferences', $conferences)
                ->with('info', [
                    'themes' => Themes::cases(),
                    'directions' => OrderDirection::cases(),
                    'orders' => OrderBy::cases(),
                    'default_theme' => $themes,
                    'default_order' => $orderBy,
                    'default_directions' => $orderDir,
                    'default_search' => $searchString,
                    'role' => auth()->user()->role,
                    ]);
        } else {
            return AdminService::invalidAccess();
        }

    }


    /**
     * Lists all users and allows searching or ordering by name.
     *
     * @return riew Returned View shows all user and allows managing them
     */
    public function usersSearch() {
        if(AdminService::amIAdmin()) {
            $orderDir = request()->input('order', 'asc');
            $searchString = request()->input('search', null);

            $users = UserService::search($orderDir, $searchString);
            return view('admin.users.search')
                ->with('users', $users)
                // return back default search/filter values, so that settings
                // wont be lost on refresh
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
     * Changes user role is current user has appropriate permissions
     *
     * @return redirect back to user dashboard
     */
    public function setRole(Request $request) {
        if(AdminService::amIAdmin()) {
            $userId = $request->input('user_id', null);
            $role = $request->input('role', null);
            $res = AdminService::setRole($userId, $role);

            return redirect()->back()
                ->with('notification', [$res]);
        } else {
            return AdminService::invalidAccess();
        }
    }
}
