<?php

namespace App\Http\Controllers;

class DemoController extends Controller
{
    public function demoUmpire()
    {
        if (logged_in_umpire_data()) {
            return redirect('umpire');
        }
    }
    public function demoLeague()
    {
        if (logged_in_league_data()) {
            return redirect('league');
        }
    }
}
