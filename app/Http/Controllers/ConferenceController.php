<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Models\Conference;
use App\Services\ConferenceService;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ConferenceController extends Controller
{
    public function __construct(private ConferenceService $conferenceService)
    {

    }

    /**
     * Returns all cards that are available, or based on filters
     *
     * @return void search view with all conferences
     */
    public function getAll() {
        $conferences = $this->conferenceService->getAllShortDescription();

        return view('conferences.search')
            ->with('conferences', $conferences);
    }

    /**
     * Returns conference based on provided id
     *
     * @param  mixed $id of the conference to get
     * @return void details of the conference view
     */
    public function get($id) {
        $conference = $this->conferenceService->get($id);

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
        $conferences = $this->conferenceService->getMy($userId);

        return view('conferences.dashboard')
            ->with("conferences", $conferences);
    }

    /**
     * Returns view for creating new conference
     *
     * @return void edit page view
     */
    public function creationForm() {
        $conference = $this->conferenceService->emptyConferenceWithDate();

        return view('conferences.edit')
            ->with('conference', $conference);
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

        $res = $this->conferenceService->create($request);
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
     * Returns edit page filled current conference data
     *
     * @param  mixed $id of the conference
     * @return void edit view
     */
    public function edit($id) {
        // get conference information
        $conference = $this->conferenceService->getWithFormattedDate($id);

        $user = auth()->user();
        // check if user is owner or admin
        if($user->id == $conference->owner_id || $user->role == RoleType::Admin->value) {

            return view('conferences.edit')
                ->with('conference', $conference)
                ->with('notification', '');
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', 'You do not have permission to edit this conference');
        }
    }


    /**
     * Show user lecture edit for a current conference
     *
     * @param  mixed $id Id of the conference
     * @return void View to lectures editing or redirects user to dashboard if
     * user doesn't have permissions
     */
    public function listConferenceLectures($id) {
        // get conference information
        $conference = $this->conferenceService->get($id);

        $user = auth()->user();
        // check if user is owner or admin
        if($user->id == $conference->owner_id || $user->role == RoleType::Admin->value) {
            return view('conferences.lectures')
                ->with('lectures', $conference->lectures)
                ->with('notification', null);
        } else {
            return redirect('conferences/dashboard')
                ->with('notification', 'You do not have permission to access lectures of this conference');
        }

    }
}
