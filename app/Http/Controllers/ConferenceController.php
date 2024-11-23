<?php

namespace App\Http\Controllers;

use App\Enums\OrderBy;
use App\Enums\OrderDirection;
use App\Enums\RoleType;
use App\Enums\Themes;
use App\Services\AdminService;
use App\Services\ConferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConferenceController extends Controller
{
    /**
     * Returns all cards that are available, or based on filters
     *
     * @return void search view with all conferences
     */
    public function search(Request $request) {
        $themes = $request->input('themes', null);
        $orderBy = $request->input('order', 'Name');
        $orderDir = $request->input('directions', 'asc');
        $searchString = $request->input('search', null);

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
                ]);
    }

    /**
     * Returns conference based on provided id
     *
     * @param  mixed $id of the conference to get
     * @return void details of the conference view
     */
    public function get($id) {
        $conference = ConferenceService::getWithLectures($id);

        if($conference === null) {
            return view('unknown');
        }
        $conference->capacity_left = ConferenceService::capacityLeft($id);

        return view('conferences.conference')
           ->with('conferences', $conference);
    }

    /**
     * Lists all conferences that are owned by the user
     *
     * @return void dashboard page view
     */
    public function dashboard() {
        $userId = auth()->user()->id;
        $conferences = ConferenceService::getMy($userId);

        return view('conferences.dashboard')
            ->with("conferences", $conferences);
    }

    /**
     * Returns view for creating new conference
     *
     * @return void edit page view
     */
    public function creationForm() {
        $conference = ConferenceService::emptyConferenceWithDate();

        return view('conferences.edit')
            ->with('conference', $conference)
            ->with('info', [
                'type' => 'create',
                'themes' => Themes::cases(),
                ]);
    }

    /**
     * Returns edit page filled current conference data
     *
     * @param  mixed $id of the conference
     * @return void edit view
     */
    public function editForm($id) {

        $user = auth()->user();
        // check if user is owner or admin
        if(self::CheckPermissions($user, $id)) {
            // get conference information
            $conference = ConferenceService::getWithFormattedDate($id);

            if($conference == null) {
                return view('unknown');
            }

            return view('conferences.edit')
                ->with('conference', $conference)
                ->with('info', [
                    'themes' => Themes::cases(),
                    'type' => 'edit',
                    'id' => $id,
                    'editingAsAdmin' => ($user->id != $conference->owner_id),
                    ]);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', ['You do not have permission to edit this conference']);
        }
    }

    /**
     * Creates new conferences based on provided parameters, parameters are
     * first validated, then used
     *
     * @param request HTTP Post request that will be validated
     *
     * @return Redirect redirects uses to the dashboard on success or back to
     * creation site if provided data are not correct
     */
    public function create(Request $request) {

        $res = ConferenceService::create($request);
        if($res == '') {
            return redirect('conferences/dashboard')
                ->with("notification", ['Conference was created successfully']);
        } else {
            return redirect('conferences/create')
                ->withInput() // returns old input so user doesn't have to type it again
                ->withErrors($res);
        }
    }

    /**
     * Creates new conferences based on provided parameters, parameters are
     * first validated, then used
     *
     * @param request HTTP Post request that will be validated
     *
     * @return Redirect redirects uses to the dashboard on success or back to
     * creation site if provided data are not correct
     */
    public function edit(Request $request) {
        $id = $request->input('id');

        $user = auth()->user();

        if(self::CheckPermissions($user, $id)) {
            $res = ConferenceService::edit($request);
        } else {
            $res = "Error: You do not have permission for this";
        }

        $notifications = [];
        if($res != '') {
            $notifications = [$res];
        } else {
            $notifications = ['Conference changes were successfully saved'];
        }

        return redirect()->back()
            ->with("notification", $notifications)
            ->withInput();
    }

    /**
     * Show lecture edit for a current conference
     *
     * @param mixed $id Id of the conference
     * @return void View to lectures editing or redirects user to dashboard if
     * user doesn't have permissions
     */
    public function listConferenceLectures($id) {
        $user = auth()->user();

        if(self::CheckPermissions($user, $id)) {
            $conference = ConferenceService::get($id);

            return view('conferences.lectures')
                ->with('conference', $conference)
                ->with('lectures', $conference->lectures)
                ->with('rooms', $conference->rooms)
                ->with('info', [
                    'editingAsAdmin' => ($user->id != $conference->owner_id),
                    ]);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', ['You do not have permission to access lectures of this conference']);
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
        $user = auth()->user();

        if(self::CheckPermissions($user, $id)) {
            $responseView = ConferenceService::getReservations($id);

            return view('conferences.reservations')
                ->with('data', $responseView);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', ['You do not have permission to access lectures of this conference']);
        }
    }

    /**
     * Confirmes reservations
     *
     * @param  Request $request POST request containing form information
     * @return void redirects user to reservations page with notification message
     */
    public function confirmReservation(Request $request) {
        $validator = Validator::make($request->all(), [
            'conferenceId' => 'required|int',
            'reservationId' => 'required|int',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $conferenceId = $request->input('conferenceId');
        $reservationId = $request->input('reservationId');

        $res = ConferenceService::confirmReservation($conferenceId, $reservationId);

        if($res) {
            return redirect('/conferences/conference/reservations/' . $conferenceId)
                ->with('notification', [$res]);
        } else {
            return redirect('/conferences/conference/reservations/' . $conferenceId)
                ->with('notification', ['Reservation was confirmed successfully!']);
        }
    }

    /**
     * Show all conference rooms
     *
     * @param int $id contains id of the conference
     * @return Object redirects user rooms view
     */
    public function listConferenceRooms($id) {
        $user = auth()->user();

        if(self::CheckPermissions($user, $id)) {
            $rooms = ConferenceService::listRooms($id);
            if ($rooms === null)
                return redirect('conferences/dashboard')
                    ->with('notification', ['Unable to load list of rooms.']);

            return view('conferences.rooms')
                ->with('id', $id)
                ->with('rooms', $rooms)
                ->with('info', [
                    'editingAsAdmin' => ($user->id != ConferenceService::getOwner($id)),
                    ]);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', ['You do not have permission to access lectures of this conference']);
        }
    }

    /**
     * Creates new room for conference
     *
     * @param Request $request POST request containing new room information
     * @return Object redirects user to current conference room list
     */
    public function createRoom(Request $request) {
        $validator = Validator::make($request->all(), [
            'conference_id' => 'required|int',
            'name' => 'required|string',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $conferenceId = $request->input('conference_id');
        $roomName = $request->input('name');
        $user = auth()->user();

        if(self::CheckPermissions($user, $conferenceId)) {
            ConferenceService::createRoom($conferenceId, $roomName);
            return redirect('/conferences/conference/rooms/'.$conferenceId);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', ['You do not have permission for this action!']);
        }
    }

    /**
     * Update specified room's name
     *
     * @param Request $request POST request containing room information
     * @return Object redirects user to current conference room list
     */
    public function updateRoom(Request $request) {
        $validator = Validator::make($request->all(), [
            'conference_id' => 'required|int',
            'room_id' => 'required|int',
            'name' => 'required|string',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $conferenceId = $request->input('conference_id');
        $roomId = $request->input('room_id');
        $newName = $request->input('name');
        $user = auth()->user();

        if(self::CheckPermissions($user, $conferenceId)) {
            $result = ConferenceService::updateRoom($conferenceId, $roomId, $newName);
            if (!$result)
                return redirect('conferences/dashboard');
            return redirect('/conferences/conference/rooms/'.$conferenceId);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', ['You do not have permission for this action!']);
        }
    }

    /**
     * Deletes specified room
     *
     * @param Request $request POST request containing room information
     * @return Object redirects user to current conference room list
     */
    public function deleteRoom(Request $request) {
        $validator = Validator::make($request->all(), [
            'conference_id' => 'required|int',
            'room_id' => 'required|int',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $conferenceId = $request->input('conference_id');
        $roomId = $request->input('room_id');
        $user = auth()->user();

        if(self::CheckPermissions($user, $conferenceId)) {
            $result = ConferenceService::deleteRoom($conferenceId, $roomId);
            if (!$result)
                return redirect('conferences/dashboard');
            return redirect('/conferences/conference/rooms/'.$conferenceId);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', ['You do not have permission for this action!']);
        }
    }

    /**
     * Deletes conference if user has appropriate privileges
     *
     * @param  string $id Id of user to be deleted
     * @return void
     */
    public function delete($id) {
        error_log('deleted' . $id);
        $user = auth()->user();
        if($user !== null && ($user->id == $id || AdminService::amIAdmin())) {
            $res = ConferenceService::delete($id);
            return redirect()->back()
                ->with('notification', [$res]);
        } else {
            return redirect()->back()
                ->withErrors('privileges', "You do not have privileges to do this action");
        }
    }


    /**
     * CheckPermissions
     *
     * @param  mixed $user User model that will be checked
     * @param  mixed $conferenceId ID of the conference that user is trying to access
     * @return bool True if user has permission to access conference, false if not
     */
    private static function CheckPermissions($user, $conferenceId) {
        if($user == null) {
            return false;
        } else if($user->role == RoleType::Admin->value) {
            return true;
        }

        if($conferenceId == null) {
            return false;
        }

        return (ConferenceService::getOwner($conferenceId) == $user->id);
    }
}
