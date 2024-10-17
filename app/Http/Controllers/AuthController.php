<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Pail\ValueObjects\Origin\Console;

class AuthController extends Controller
{

    public function newRegistration(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required|min:3|max:40',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if(auth()->attempt(request()->only(['email', 'password']))) {
            return redirect('/profile');
        }

        return redirect('profile')->with('success', 'Account created successfully');
    }

    public function login() {

        validator(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if(auth()->attempt(request()->only(['email', 'password']))) {
            return redirect('/home');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request) : RedirectResponse {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/home');
    }
}
