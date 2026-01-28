<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @return mixed
     */
    public function profile(Request $request)
    {
        return $request->user();
    }
}
