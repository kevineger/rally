<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpRAW\phpRAW as phpRAW;
use App\Http\Requests;

class PagesController extends Controller
{
    public function showUser(Request $request)
    {
        $phpraw = new phpRAW();
        dd($request->input());
    }

    public function redditHomePage()
    {
        $phpraw = new phpRAW();
//        dd($phpraw->getMe());
        dd($phpraw->getAboutUser("LowLanding"));
    }
}
