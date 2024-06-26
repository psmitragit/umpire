<?php

namespace App\Http\Controllers;

use App\Models\UserModel;

class DemoController extends Controller
{
    public function demoUmpire()
    {
        if (logged_in_umpire_data()) {
            return redirect('umpire');
        }
        $demoUmpIds = [132, 133, 134, 135, 136, 137, 138, 139, 140];
        $demoUmpId = $demoUmpIds[array_rand($demoUmpIds)];

        $user = UserModel::find($demoUmpId);
        session(['umpire_data' => $user]);
        return redirect('umpire');
    }
    public function demoLeague()
    {
        if (logged_in_league_data()) {
            return redirect('league');
        }
        $user = UserModel::find(131);
        session(['league_data' => $user]);
        return redirect('league');
    }
}
