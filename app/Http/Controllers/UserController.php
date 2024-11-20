<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getPerson($id) {

        $data = UserService::getWithLectures($id);

        $user = auth()->user();
        if($user != null) {
            $isMyProfile = ($user->id == $id);
        } else {
            $isMyProfile = false;
        }

        return view('users.person')
            ->with('person', $data['user'])
            ->with('info', [
                'myself' => $isMyProfile,
                'editing' => false,
                ])
            ->with('futureLectures', $data['futureLectures'])
            ->with('pastLectures', $data['pastLectures']);
    }

    public function profile() {
        $id = auth()->user()->id;
        if($id != null) {
            return redirect('/users/search/' . (string)$id);
        }
    }

    public function editUser(Request $request) {
        $currentUser = auth()->user();
        $editingUserId = $request->input('user_id');

        if($currentUser != null) {
            $isMyProfile = ($currentUser->id == $editingUserId);
        } else {
            $isMyProfile = false;
        }

        error_log((string)$editingUserId);
        if($editingUserId == $currentUser->id || $currentUser->role == RoleType::Admin->value) {
            $res = UserService::editUser($request);

            if($res == '') {
                return redirect()->back()
                    ->with("notification", ['Conference changes were successfully saved'])
                    ->with('info', [
                        'myself' => $isMyProfile,
                        'editing' => true,
                    ]);
            } else {
                error_log('x');
                return redirect('/users/search/' . ((string)$editingUserId))
                    ->withInput()
                    ->withErrors($res)
                    ->with('info', [
                        'myself' => $isMyProfile,
                        'editing' => true,
                    ]);
            }
        }
    }

}
