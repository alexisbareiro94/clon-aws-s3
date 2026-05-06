<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    /**
     * Display the user settings view.
     */
    public function __invoke(Request $request)
    {
        $tokens = $request->user()->tokens;

        return view('settings.index', compact('tokens'));
    }
}
