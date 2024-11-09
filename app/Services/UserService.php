<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function getPerson($id) {
        return User::find($id);
    }
}
