<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @return mixed
     */
    public function profile(Request $request)
    {
        return $request->user('api');
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user('api');

        $user->fill($request->all())->save();

        return $user;
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user('api');

        $user->password = Hash::make($request->input('password'));

        $user->save();

        return $user;
    }
}
