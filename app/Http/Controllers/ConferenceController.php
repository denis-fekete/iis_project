<?php

namespace App\Http\Controllers;

use App\Enums\OrderBy;
use App\Enums\OrderDirection;
use App\Enums\RoleType;
use App\Enums\Themes;
use App\Models\Conference;
use App\Services\ConferenceService;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ConferenceController extends Controller
{
    /**
     * Returns all cards that are available, or based on filters
     *
     * @return void search view with all conferences
     */
    public function getAll($themes, $orderBy, $orderDir) {
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

        return view('conferences.conference')
            ->with('conferences', $conference)
            ->with('notification', null);
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
            ->with('info', ['type' => 'create']);
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

        $user = auth()->user();
        // check if user is owner or admin
        if($user->id == $conference->owner_id) {

            return view('conferences.edit')
                ->with('conference', $conference)
                ->with('info', ['type' => 'edit', 'id' => $id]);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', 'You do not have permission to edit this conference');
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
                ->with("notification", 'Conference was created successfully');
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

        if($id == null) {
            return redirect('conferences/dashboard')
                ->withErrors(['id' => 'Error: Unknown conference ID']);
        }

        $res = ConferenceService::edit($request);

        if($res == '') {
            return redirect('conferences/dashboard')
                ->with("notification", 'Conference changes were successfully saved');
        } else {
            return redirect('conferences/edit/' . ((string)$id))
                ->withInput() // returns old input so user doesn't have to type it again
                ->withErrors($res);
        }
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
        if($user->id == $conference->owner_id || $user->role == RoleType::Admin->value) {
            return view('conferences.lectures')
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
     * Changes values of confirmed lectures
     *
     * @param  Request $request POST request containing form information
     * @return void redirects user to lectures page with notification message
     */
    public function editLecturesList(Request $request) {
        $id = $request->input('id');

        $res = ConferenceService::editLecturesList($request);

        if($res) {
            return redirect('/conferences/conference/lectures/' . $id)
                ->with('notification', "Your changes were saved");
        } else {
            return redirect('/conferences/conference/lectures/' . $id)
                ->with('notification', "Something went wrong, try it again later"); // TODO: change to ERROR
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
        if($user->id == $conference->owner_id) {
            return view('conferences.reservations')
                ->with('id', $conference->id)
                ->with('reservations', $conference->reservations)
                ->with('notification', null);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', 'You do not have permission to access lectures of this conference');
        }
    }

    /**
     * Changes values of confirmed reservations
     *
     * @param  Request $request POST request containing form information
     * @return void redirects user to reservations page with notification message
     */
    public function editReservationsList(Request $request) {
        $id = $request->input('id');

        $res = ConferenceService::editReservationsList($request);

        if($res) {
            return redirect('/conferences/conference/reservations/' . $id)
                ->with('notification', "Your changes were saved");
        } else {
            return redirect('/conferences/conference/lectures/' . $id)
                ->with('notification', "Something went wrong, try it again later"); // TODO: change to ERROR
        }
    }

}
