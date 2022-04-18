<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        return view('landing');
    }
}
