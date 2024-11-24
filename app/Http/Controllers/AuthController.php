<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Registers new user into system if credentials are valid (not too long,
     * not too short, etc...), if not errors are send back for user to correct
     * them
     *
     * @param  Request $request Input with registration data
     * @return redirect to the url where user was before registration
     */
    public function registration(Request $request)
    {
        $res = UserService::create($request);

        $returnUrl = $request->input('return_to');

        if($res == '') {
            // login in user after new user was created
            if(auth()->attempt(request()->only(['email', 'password']))) {
                if($returnUrl !== null) {
                    return redirect($returnUrl);
                } else {
                    return redirect('/');
                }
            }
        } else {
            return redirect()->back()
                ->withErrors(['auth' => $res])
                ->withInput();
        }
    }

    /**
     * Logs in existing user, if credentials are wrong (don't exist in system)
     * user will be given error messages
     *
     * @param  Request Input with login data
     * @return redirect to the url where user was before login
     */
    public function login(Request $request) {
        validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        $returnUrl = $request->input('return_to');

        // attempt to login in user
        if(auth()->attempt(request()->only(['email', 'password']))) {
            if( $returnUrl !== null) {
                return redirect($returnUrl);
            } else {
                return redirect('/home');
            }
        }

        if($returnUrl !== null) {
            return redirect($returnUrl)->withErrors(['email' => 'Invalid credentials']);
        } else {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    /**
     * Logs out logged in user -> invalidates session (cookie) and user will be
     * logged out
     */
    public function logout(Request $request) : RedirectResponse {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->back();
    }

    /**
     * @return view returns authentication view
     */
    public function authGET() {
        return view('auth.auth');
    }
}
