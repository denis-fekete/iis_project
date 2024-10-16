<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function create()
    {
        return view('form'); // points to resources/views/form.blade.php
    }

    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = User::create($validatedData);

        return response()->json($user);
        // return view('form')->with('data', $data); // Redirect or show results
    }
}
