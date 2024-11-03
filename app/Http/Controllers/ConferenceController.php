<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Events\Validated;
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

class TmpConference {
    public int $id;
    public string $title;
    public string $description;
    public string $theme;
    public string $start_time;
    public string $end_time;
    public string $place_address;
    public int $price;
    public int $capacity;
    public int $owner_id;

    public array $lectures;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->title = fake()->words(4, true);
        $this->description = fake()->text(600);
        $this->theme = fake()->words(3, true);
        $this->start_time = fake()->date();
        $this->end_time = $this->start_time;
        $this->place_address = fake()->address();
        $this->price = fake()->numberBetween(0, 500);
        $this->capacity = fake()->numberBetween(0, 500);

        $userCount = DB::table('user')->count();
        $this->owner_id = fake()->numberBetween(0, $userCount);

        $numberOfLectures = fake()->numberBetween(1, $userCount);

        for($i = 0; $i < $numberOfLectures; $i++)
        {
            $this->lectures[] = new TmpLecture($this->id);
        }
    }
}

class ConferenceController extends Controller
{
    // DEBUG:
    private ?array $cards = null;


    private function generateCards() {
        if($this->cards == NULL)
        {
            for($i = 0; $i < 10; $i++)
            {
                $this->cards[$i] = new TmpConference($i);
            }
        }
    }
    // END OF DEBUG:

    /**
     * Returns all cards that are available, or based on filters
     */
    public function getAll() {
        $this->generateCards(); // DEBUG:

        return view('conferences.search')->with('cards', $this->cards);
    }

    /**
     * Returns conference based on provided id
     */
    public function get($id) {
        $this->generateCards(); // DEBUG:

        // encode structure into json, decode it into a array
        $conference = json_decode(json_encode($this->cards[$id]),  true);

        // get name of the owner based on owner_id
        $conference["owner_name"] = DB::table('user')
            ->where('id', $conference["owner_id"])
            ->value('name');

        // add speaker_name from speaker_id
        foreach($conference["lectures"] as $lecture => &$obj) {
            $obj["speaker_name"] = DB::table('user')
                ->where('id', $obj['speaker_id'])
                ->value('name');
        }

        // TODO: do the same for the room info based on room id

        return view('conferences.conference')->with($conference)->with('notification', null);
    }

    /**
     * Lists all conferences that are owned by the user
     */
    public function dashboard() {
        //TODO: logic of getting user created conferences
        // DEBUG:
        $this->generateCards(); // DEBUG:
        $count = fake()->numberBetween(0, 3);
        $conferences = [];
        for($i = 0; $i < $count; $i++) {
            $conferences[] = $this->cards[$i];
        }
        // END OF DEBUG:

        return view('conferences.dashboard')
            ->with("cards", $conferences);
    }

    /**
     * Returns view for creating new conference
     */
    public function createGET() {
        return view('conferences.edit');
    }

    /**
     * Creates new conferences based on provided parameters, parameters are
     * first validated, then used
     */
    public function createPOST(Request $request) {

        $validator = Validator::make($request->all(), [
                'title' => 'required|min:3|max:100',
                'description' => 'required|min:20|max:10000',
                'theme' => 'required|min:2|max:100',
                'start_time' => 'required|date',
                'end_time' => 'required|date',
                'place_address' => 'required',
                'price' => 'required',
                'capacity' => 'required',
        ]);

        if($validator->fails()) {
            $errorMessages = collect($validator->errors()->toArray())
                ->flatten()
                ->implode("\n"); // Joins each error with a newline


            return redirect('conferences/edit')
                ->with("notification", $errorMessages);
        }

        $validated = $validator->validated();

        $conference = Conference::create([
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
            ->with('notification', 'Conference was created successfully');
    }

    public function edit() {

    }

    public function lectures($id) {
        $this->generateCards(); // DEBUG:

        // || 1 for DEBUG:
        if($this->cards[$id]->owner_id == auth()->user()->id || 1) {

            $lectures = $this->cards[$id]->lectures;
            return view('conferences.lectures')
                ->with('lectures', $lectures)
                ->with('notification', null);
        } else {
            return view('conferences.lectures')
                ->with('notification', 'You do not own this conference')
                ->with('lectures', array());
        }
    }
}
