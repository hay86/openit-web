<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Auth;

class UserAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {
        return Auth::user();
    }
}
