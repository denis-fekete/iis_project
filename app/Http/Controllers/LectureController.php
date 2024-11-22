<?php

namespace App\Http\Controllers;

use App\Services\LectureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    *   Lists all conferences that are owned by the user
    */
    public function dashboard() {
        $userId = auth()->user()->id;
        $lecures = LectureService::getLecturesAssignedToUser($userId);

        return view('lectures.dashboard')->with('cards', $lecures);
    }

    /**
    *   Shows info about lecture
    */
    public function get($id) {

        $user = auth()->user();

        if($user !== null) {
            $userId = $user->id;
        }
        $userId = null;

        $data = LectureService::getLectureDetailView($id, $userId);
        if($data == null) {
            return view('unknown');
        }
        return view('lectures.lecture')->with('data', $data);
    }

    /**
    *   Returns edit view for chosen lecture
    */
    public function editGET($id) {
        $userId = auth()->user()->id;
        if ($policyCheckResult = LectureService::checkEditPolicy($id, $userId))
            return redirect()->back()->with('error', $policyCheckResult);

        $lecture = LectureService::getLectureById($id);
        return view('lectures.edit')
            ->with("info", $lecture);
    }

    /**
    *   Stores changes
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
            return redirect()->back()->with('error', $policyCheckResult);

        LectureService::updateLectureInfo($request->input('id'), $request);

        return $this->get($id);
    }

    /**
    *   Shows view for creating new lecture
    */
    public function createGET($conference_id) {
        $createEmpty = $this->emptyInfo;
        $createEmpty['conference_id'] = $conference_id;
        return view('lectures.create')
            ->with('info', $createEmpty);
    }

    /**
    *   Creates new lecture
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
        return $this->dashboard();
    }

    /**
    *   Cancels lecture
    */
    public function cancel(Request $request) {
        $lectureId = $request->input('id');
        $userId = auth()->user()->id;

        $checkPolicyError = LectureService::checkEditPolicy($lectureId, $userId);
        if ($checkPolicyError)
            return redirect()->back()->with('error', $checkPolicyError);

        $cancellationError = LectureService::cancelLecture($userId, $lectureId);
        if ($cancellationError)
            return redirect()->back()->with('error', $cancellationError);

        return $this->dashboard();
    }

    /**
    *   Confirms lecture
    */
    public function confirm(Request $request) {
        $validator = Validator::make($request->all(), [
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

        return redirect('/conferences/conference/lectures/'.$request->conferenceId);
    }

    /**
    *   Unconfirms lecture
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
