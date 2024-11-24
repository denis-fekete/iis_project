<?php

namespace App\Http\Controllers;

use App\Services\LectureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LectureController extends Controller
{
    private array $emptyInfo = [
        "title" => "",
        "description" => "",
        "poster" => "",
        "start_time" => "",
        "end_time" => "",
        "conference_id" => "",
    ];

    /**
     * Lists all conferences that are owned by the user
     * @return view lectures dashboard view with list of lecture list view models $cards
     *  (id, title, conferenceId, conferenceTitle, isConfirmed)
     */
    public function dashboard() {
        $userId = auth()->user()->id;
        $lecures = LectureService::getLecturesAssignedToUser($userId);
        return view('lectures.dashboard')->with('cards', $lecures);
    }

    /**
     * Shows info about lecture
     * @param int $id id of the lecture
     * @return view lecture view with lecture data $data
     *  (id, title, description, posterUrl, startTime, endTime, ownerId, ownerName, room, conferenceName, conferenceId, isConfirmed, canEdit)
     */
    public function get($id) {
        $user = auth()->user();
        if (!$user)
            $userId = null;
        else
            $userId = $user->id;

        $data = LectureService::getLectureDetailView($id, $userId);
        if($data == null) {
            return view('unknown');
        }
        return view('lectures.lecture')->with('data', $data);
    }

    /**
     * Returns edit view for chosen lecture
     * @param int $id id of lecture to edit
     * @return view lecture edit view with lecture data $info
     *  (id, title, description, poster, is_confirmed, start_time, end_time, conference_id, speaker_id, room_id)
     */
    public function editGET($id) {
        $userId = auth()->user()->id;
        if ($policyCheckResult = LectureService::checkEditPolicy($id, $userId))
            return redirect('/lectures/dashboard')->with('notification', [$policyCheckResult]);

        $lecture = LectureService::getLectureById($id);
        return view('lectures.edit')
            ->with("info", $lecture);
    }

    /**
     * Stores lecture changes
     * @param Request $request requst data (title, description, poster)
     * @return view updated lecture detial view $data
     *  (id, title, description, posterUrl, startTime, endTime, ownerId, ownerName, room, conferenceName, conferenceId, isConfirmed, canEdit)
     */
    public function editPOST(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'poster' => 'nullable|url',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $id = $request->input('id');
        $userId = auth()->user()->id;

        if ($policyCheckResult = LectureService::checkEditPolicy($id, $userId))
            return redirect('/lectures/dashboard')->with('notification', $policyCheckResult);

        LectureService::updateLectureInfo($request->input('id'), $request);

        return $this->get($id);
    }

    /**
     * Shows view for creating new lecture
     * @param int $conference_id id of the conference the lecture should be created for
     * @return view empty lecture view with empty lecture object $info (title, description, poster, start_time, end_time, conference_id)
     */
    public function createGET($conference_id) {
        $createEmpty = $this->emptyInfo;
        $createEmpty['conference_id'] = $conference_id;
        return view('lectures.create')
            ->with('info', $createEmpty);
    }

    /**
     * Creates new lecture
     * @param Request $resuest request data with new lecture data
     * @return redirect redirects user to dashboard
     */
    public function createPOST(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'poster' => 'nullable|url',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        LectureService::createLecture(auth()->user()->id, $request);
        return redirect('/lectures/dashboard');
    }

    /**
     * Cancels lecture
     * @param Request $request cancellation data (id)
     * @return redirect redirects user to lecture dashboard
     */
    public function cancel(Request $request) {
        $lectureId = $request->input('id');
        $userId = auth()->user()->id;

        $checkPolicyError = LectureService::checkEditPolicy($lectureId, $userId);

        if ($checkPolicyError)
            return redirect('/lectures/cancel')->with('notification', [$checkPolicyError]);

        $cancellationError = LectureService::cancelLecture($lectureId);
        if ($cancellationError)
            return redirect()->back()->with('error', $cancellationError);

        return $this->dashboard();
    }

    /**
     * Confirms lecture
     * @param Request $request data of lecture confirmation
     * @return redirect redirects user to list of lectures for specified conference
     */
    public function confirm(Request $request) {
        $validator = Validator::make($request->all(), [
            'conferenceId' => 'required|int',
            'id' => 'required|int',
            'startTime' => 'required|date',
            'endTime' => 'required|date|after:startTime',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $lectureId = $request->input('id');
        $userId = auth()->user()->id;

        $checkPolicyError = LectureService::checkSchedulePolicy($lectureId, $userId);
        if ($checkPolicyError)
            return redirect()->back()->with('notification', array($checkPolicyError));

        $confirmError = LectureService::confirm($request);
        if ($confirmError)
            return redirect()->back()->with('notification', array($confirmError));

        return redirect('/conferences/conference/lectures/'.$request->input('conferenceId'));
    }

    /**
     * Unconfirms lecture
     * @param Request $request data of unconfirmation (id)
     * @return redirect redirects user to list of lectures for specified conference
     */
    public function unconfirm(Request $request) {
        $lectureId = $request->input('id');
        $userId = auth()->user()->id;

        $checkPolicyError = LectureService::checkSchedulePolicy($lectureId, $userId);
        if ($checkPolicyError)
            return redirect()->back()->with('error', $checkPolicyError);

        $unconfirmError = LectureService::unconfirm($lectureId);
        if ($unconfirmError)
            return redirect()->back()->with('error', $unconfirmError);

        return redirect('/conferences/conference/lectures/'.$request->conferenceId);
    }
}
