<?php

namespace App\Services;

use App\Enums\OrderDirection;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class UserService
{
    private const VALIDATOR_PARAMS_CREATE = [
        'name' => 'min:3|max:15',
        'surname' => 'min:3|max:15',
        'new_password' => 'min:8',
        'title_after' => 'max:10',
        'title_before' => 'max:10',
        'description' => 'max:2000',
        'email' => 'required|email|unique:user,email',
        'password' => 'required|confirmed|min:8',
    ];

    private const VALIDATOR_PARAMS_EDIT = [
        'name' => 'min:3|max:15',
        'surname' => 'min:3|max:15',
        'password' => 'required|min:8',
        'new_password' => 'min:8',
        'title_after' => 'max:10',
        'title_before' => 'max:10',
        'description' => 'max:2000',
    ];


    /**
     * Returns array with User model, array of Lectures that are in future and array
     * of Lectures models that already happened
     *
     * @param string $id ID of the user whose data will be returned
     * @return array With key `user` containing User model, key `futureLectures` with
     * array of future Lecture models and key `pastLectures` with array of past
     * Lecture models
     */
    public static function getWithLectures($id) {
        $user =  User::find($id);

        $future = Lecture::where('speaker_id', $id)
            ->where('end_time', '>=', Carbon::now())
            ->orderBy('end_time', 'desc')
            ->get();

        $past = Lecture::where('speaker_id', $id)
            ->where('end_time', '<', Carbon::now())
            ->orderBy('end_time', 'desc')
            ->get();

        return ['user' => $user,
                'futureLectures' => $future,
                'pastLectures' => $past,
                ];
    }

    /**
     * Returns all users
     *
     * @return Collection
     */
    public static function getAll() : Collection {
        return User::all();
    }

    /**
     * Returns user by provided parameters
     *
     * @param  OrderDirection|string|null $orderDir order direction, ascending or descending
     * @param  string|null $searchString searching in name, surname, or title of the users
     * @return Collection
     */
    public static function search($orderDir = null, $searchString = null) : Collection {
        $query = User::query();

        if($searchString != null && $searchString != '%') {
            $query->where('name', 'LIKE', '%' . $searchString . '%')
                ->orWhere('surname', 'LIKE', '%' . $searchString . '%')
                ->orWhere('title_before', 'LIKE', '%' . $searchString . '%')
                ->orWhere('title_after', 'LIKE', '%' . $searchString . '%');
        }

        switch($orderDir) {
            case OrderDirection::Descending->value:
            case OrderDirection::Ascending->value:
                break;
            default:
                $orderDir = 'asc';
                break;
        }


        $result = $query->get();

        return $result;
    }

    /**
     * Edits user data based on input $request
     *
     * @param  Request $request
     * @return string Error code/message, if no error occurred empty string ('')
     * is returned
     */
    public static function editUser(Request $request) {
        $validator =  Validator::make($request->all(), self::VALIDATOR_PARAMS_EDIT);

        if($validator->fails()) {
            // return error messages as string
            $errorMessages = collect($validator->errors()->toArray())
                ->flatten()
                ->implode("\n");

            return $errorMessages;
        }

        $validated = $validator->validated();

        $id = $request->input('user_id');
        $user = User::find($id);

        if($user == null) {
            return "User doesn't exist";
        }
        if(Hash::check($validated['password'], $user->password) == false) {
            return "Password doesn't match";
        }

        if($user == null) {
            return "Error: provided conference doesn't exist";
        }

        if(isset($validated['name']))
            $user->name = $validated['name'];
        if(isset($validated['surname']))
            $user->surname = $validated['surname'];
        if(isset($validated['new_password']))
            $user->password = Hash::make($validated['new_password']);
        if(isset($validated['title_before']))
            $user->title_before = $validated['title_before'];
        if(isset($validated['title_after']))
            $user->title_after = $validated['title_after'];
        if(isset($validated['description']))
            $user->description = $validated['description'];

        $user->save();

        return ''; // everything was alright, return no error message
    }

    public static function create(Request $request) {
        $validator =  Validator::make($request->all(), self::VALIDATOR_PARAMS_CREATE);

        if($validator->fails()) {
            $errorMessages = collect($validator->errors()->toArray())
                ->flatten()
                ->implode("\n");

            return $errorMessages;
        }

        $validated = $validator->validated();

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),

        ]);

        if(isset($validated['name']))
            $user->name = $validated['name'];
        if(isset($validated['surname']))
            $user->surname = $validated['surname'];
        if(isset($validated['new_password']))
            $user->password = Hash::make($validated['new_password']);
        if(isset($validated['title_before']))
            $user->title_before = $validated['title_before'];
        if(isset($validated['title_after']))
            $user->title_after = $validated['title_after'];
        if(isset($validated['description']))
            $user->description = $validated['description'];

        $user->save();

        return ''; // everything was alright, return no error message
    }

    public static function delete($id) {
        $userObj = User::find($id);

        if($userObj != null) {
            $userObj->delete();
        }
    }
}
