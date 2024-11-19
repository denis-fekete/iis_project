<?php

namespace App\Http\Controllers;

use App\Enums\OrderBy;
use App\Enums\OrderDirection;
use App\Enums\RoleType;
use App\Enums\Themes;
use App\Services\ConferenceService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminConferenceController extends Controller
{
    /**
     * Returns all cards that are available, or based on filters
     *
     * @return void search view with all conferences
     */
    public function search($themes, $orderBy, $orderDir) {
        $conferences = ConferenceService::getAllShortDescription($themes, $orderBy, $orderDir);

        return view('conferences.search')
            ->with('conferences', $conferences)
            ->with('info', [
                'themes' => Themes::cases(),
                'directions' => OrderDirection::cases(),
                'orders' => OrderBy::cases(),
                'default_theme' => $themes,
                'default_orders' => $orderBy,
                'default_directions' => $orderDir,
                'role' => auth()->user()->role,
                ]);
    }

    /**
     * Redirects admin to default search for conferences
     *
     * @return void search view with all conferences
     */
    public function searchDefault() {
        return redirect('/admin/conferences/search/All;Name;asc');
    }

}
