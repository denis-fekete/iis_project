<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
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
     */
    public function registration(Request $request)
    {
        // validate data using built-in validator method
        // if validation fails laravel will automatically send errors and method will end
        $validated = request()->validate([
            'name' => 'required|min:3|max:40', // must be present
            'surname' => 'required|min:3|max:40', // must be present
            'email' => 'required|email|unique:user,email', // must be present, must be email, must unique in user in column email
            'password' => 'required|confirmed|min:8' // confirmed -> must come with second parameter for confirmation
        ]);


        // create user with validated data, hash the password so its not store in plain text
        $user = User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $returnUrl = $request->input('return_to');

        // log in user and send him to his profile
        if(auth()->attempt(request()->only(['email', 'password']))) {
            if($returnUrl !== null) {
                return redirect($returnUrl);
            }
        }
    }

    /**
     * Logs in existing user, if credentials are wrong (don't exist in system)
     * user will be given error messages
     */
    public function login(Request $request) {
        validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        $returnUrl = $request->input('return_to');

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
}
