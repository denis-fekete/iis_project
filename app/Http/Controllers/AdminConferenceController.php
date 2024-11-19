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
    public function dashboard($themes, $orderBy, $orderDir) {
        $conferences = ConferenceService::getAllShortDescription($themes, $orderBy, $orderDir);

        return view('admin.conferences.dashboard')
            ->with('conferences', $conferences)
            ->with('info', [
                'themes' => Themes::cases(),
                'directions' => OrderDirection::cases(),
                'orders' => OrderBy::cases(),
                'default_theme' => $themes,
                'default_orders' => $orderBy,
                'default_directions' => $orderDir,
                ]);
    }

        /**
     * Returns edit page filled current conference data
     *
     * @param  mixed $id of the conference
     * @return void edit view
     */
    public function editForm($id) {
        // get conference information
        $conference = ConferenceService::getWithFormattedDate($id);

        return view('admin.conferences.edit')
            ->with('conference', $conference)
            ->with('info', ['type' => 'edit', 'id' => $id, 'role' => 'admin']);
    }

    /**
     * Show lecture edit for a current conference
     *
     * @param  mixed $id Id of the conference
     * @return void View to lectures editing or redirects user to dashboard if
     * user doesn't have permissions
     */
    public function listConferenceLectures($id) {
        // get conference information
        $conference = ConferenceService::get($id);

        $user = auth()->user();
        // check if user is owner or admin
        if($user->role == RoleType::Admin->value) {
            return view('admin.conferences.lectures')
                ->with('conference', $conference)
                ->with('lectures', $conference->lectures)
                ->with('notification', null)
                ->with('rooms', $conference->rooms);

        } else {
            return redirect('conferences/dashboard')
                ->with('notification', 'You do not have permission to access lectures of this conference');
        }
    }

    /**
     * Show reservations and allow editing for a current conference
     *
     * @param  mixed $id Id of the conference
     * @return void View to reservation editing or redirects user to dashboard if
     * user doesn't have permissions
     */
    public function listConferenceReservations($id) {
        // get conference information
        $conference = ConferenceService::getWithReservations($id);

        $user = auth()->user();

        // check if user is owner or admin
        if($user->role == RoleType::Admin->value) {
            return view('admin.conferences.reservations')
                ->with('id', $conference->id)
                ->with('reservations', $conference->reservations)
                ->with('notification', null)
                ->with('info', ['role' => 'admin']);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', 'You do not have permission to access lectures of this conference');
        }
    }
}
