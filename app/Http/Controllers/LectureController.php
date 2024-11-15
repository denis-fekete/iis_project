<?php

namespace App\Http\Controllers;

use App\Services\LectureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

// DEBUG: debug only class
Class TmpLecture {
    public int $id;
    public string $title;
    public string $poster;
    public bool $is_confirmed;
    public string $start_time;
    public string $end_time;

    public int $conference_id;
    public int $speaker_id;
    public int $room_id;

    public function __construct(int $id) {
        $this->id = fake()->numberBetween(0, 10000);
        $this->title = fake()->words(2, true);
        $this->poster = "placeholder poster";
        $this->is_confirmed = fake()->boolean();
        $this->start_time = fake()->date();
        $this->end_time = $this->start_time;
        $this->conference_id = $id;
        $this->speaker_id = fake()->numberBetween(0, DB::table('user')->count());
        $this->room_id = fake()->numberBetween(0, 10000);
    }
}

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
        $data = LectureService::getLectureDetailView($id);
        return view('lectures.lecture')->with('data', $data);
    }

    /**
    *   Returns edit view for chosen lecture
    */
    public function editGET($id) {
        // TODO: CHECK POLICY
        $lecture = LectureService::getLectureById($id);
        return view('lectures.edit')
            ->with("info", $lecture);
    }

    /**
    *   Stores changes 
    */
    public function editPOST(Request $request) {
        // TODO: CHECK POLICY
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'poster' => 'nullable|url',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $id = $request->input('id');
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
            'poster' => 'nullable|url',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
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

        $result = LectureService::cancelLecture($userId, $lectureId);
        if (!$result)
            return $this->dashboard();

        return redirect()->back()->withErrors($result)->withInput();
    }
}
