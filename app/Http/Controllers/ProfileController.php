<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
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
    public function updateProfile(UpdateRequest $request)
    {
        $user = $request->user('api');

        $user->fill($request->validated())->save();

        return $user;
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user('api');

        $user->password = $request->validated()['password'];

        $user->save();

        return $user;
    }
}
