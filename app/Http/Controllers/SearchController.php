<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SearchController extends Controller
{
    public function getPerson($id) {

        $user = DB::table('user')->where('id', $id)->get();
        $user = json_decode(json_encode($user), true);
        // the [0] is because $user is returned as a array that contains one array inside
        return view('search.person')->with($user[0]);
    }
}
