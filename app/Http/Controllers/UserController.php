<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Services\AdminService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Returns profile page of the user
     *
     * @param  string $id User id
     * @return void View of the user page with correct data
     */
    public function getPerson($id) {

        $data = UserService::getWithLectures($id);

        $user = auth()->user();
        if($user !== null) {
            $canEdit = ($user->id == $id || AdminService::amIAdmin());
        } else {
            $canEdit = false;
        }

        if($data['user'] == null) {
            return view('unknown');
        }

        return view('users.person')
            ->with('person', $data['user'])
            ->with('info', [
                'canEdit' => $canEdit,
                'editing' => false,
                ])
            ->with('futureLectures', $data['futureLectures'])
            ->with('pastLectures', $data['pastLectures']);
    }

    /**
     * Redirects user to the user search for the current user
     *
     * @return void
     */
    public function profile() {
        $id = auth()->user()->id;
        if($id !== null) {
            return redirect('/users/search/' . (string)$id);
        }
    }

    /**
     * Edits user data based on $request data
     *
     * @param  Request $request Input data with that should be changed
     * @return void Redirects user back to editing view
     */
    public function editUser(Request $request) {
        $currentUser = auth()->user();
        $editingUserId = $request->input('user_id');

        if($currentUser !== null) {
            $isMyProfile = ($currentUser->id == $editingUserId);
        } else {
            $isMyProfile = false;
        }

        if($editingUserId == $currentUser->id || $currentUser->role == RoleType::Admin->value) {
            $res = UserService::editUser($request);

            if($res == '') {
                return redirect('/users/search/' . ((string)$editingUserId))
                    ->with("notification", ['Conference changes were successfully saved'])
                    ->with('info', [
                        'myself' => $isMyProfile,
                        'editing' => true,
                    ]);
            } else {
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

    /**
     * Deletes user if user has appropriate privileges
     *
     * @param  string $id Id of user to be deleted
     * @return void
     */
    public function delete(Request $request) {
        $userToDelete = $request->input('user_id');
        $user = auth()->user();

        if($user !== null && ($user->id == $userToDelete || AdminService::amIAdmin())) {
            $res = UserService::delete($userToDelete);
            return redirect()->back()
                ->with('notification', [$res]);
        } else {
            return redirect()->back()
                ->withErrors('privileges', "You do not have privileges to do this action");
        }
    }

}
