<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser'   => $user,
            'activities'    => Activity::feed($user),
        ]);
    }
}
