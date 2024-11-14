<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
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
    *   Returns edit view for chosen lecture
    */
    public function editGET($id) {
        $lecture = LectureService::getLectureById($id);
        return view('lectures.edit')
            ->with("info", $lecture);
    }

    /**
    *   Stores changes 
    */
    public function editPOST($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'poster' => 'url',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        LectureService::updateLectureInfo($id, $request);

        return $this->dashboard();
    }

    /**
     * Returns view for creating new lecture
     */
    public function createGET() {
        return view('lectures.edit')
            ->with('info', $this->emptyInfo);
    }



    /**
     * Cancels lecture
     */
    public function cancel() {
        //TODO:
        return redirect('lectures/dashboard')
            ->with('notification', 'Lecture deleted successfully');
    }

    /**
     * Creates new lecture based on provided parameters, parameters are
     * first validated, then used
     */
    public function createPOST(Request $request) {

        $validator = Validator::make($request->all(), [
                'title' => 'required|min:3|max:100',
                'poster' => 'max:1000',
                'start_time' => 'required|date',
                'end_time' => 'required|date',
        ]);

        if($validator->fails()) {
            $errorMessages = collect($validator->errors()->toArray())
                ->flatten()
                ->implode("\n"); // Joins each error with a newline


            return redirect('conferences/edit')
                ->with("notification", $errorMessages)
                ->with("info", $this->emptyInfo);

        }

        $validated = $validator->validated();

        $lecture = Lecture::create([
            'title' => $validated['title'],
            'description' => $validated['description'], // must be present, must be email, must unique in user in column email
            'theme' => $validated['theme'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'place_address' => $validated['place_address'],
            'price' => $validated['price'],
            'capacity' => $validated['capacity'],
            'owner_id' => auth()->user()->id,
        ]);

        return view('conferences/dashboard')
            ->with('notification', 'Conference was created successfully')
            ->with('info', $this->emptyInfo);
    }


}
