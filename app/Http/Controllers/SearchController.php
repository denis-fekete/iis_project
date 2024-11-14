<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SearchController extends Controller
{
    public function getPerson($id) {

        $user = UserService::getPerson($id);

        return view('search.person')
            ->with('person', $user);
    }
}
