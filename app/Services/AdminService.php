<?php

namespace App\Services;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminService
{
    /**
     * Checks if current use has administrator privileges
     *
     * @return boolean True if current user is admin (has admin role), false otherwise
     */
    public static function amIAdmin() {
        $user = auth()->user();

        if($user != null) {
            return ($user->role == RoleType::Admin->value);
        } else {
            return false;
        }
    }

    /**
     * Redirects user back and sends error message signaling user that it doesn't have
     * needed privileges
     */
    public static function invalidAccess() {
        return redirect()->back()
            ->withErrors(['auth' => "You do not have permissions for this action"]);
    }
}
