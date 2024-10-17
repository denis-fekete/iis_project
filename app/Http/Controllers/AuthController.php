<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Pail\ValueObjects\Origin\Console;

class AuthController extends Controller
{
    public function store(Request $request)
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

        return redirect('profile')->with('success', 'Account created successfully');
    }

    public function login()
    {

        validator(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if(auth()->attempt(request()->only(['email', 'password'])))
        {
            return redirect('/admin');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }
}
