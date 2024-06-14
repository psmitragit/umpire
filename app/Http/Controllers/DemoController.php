<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserModel;
use App\Models\LeagueModel;
use App\Models\UmpireModel;
use Illuminate\Http\Request;
use App\Models\UmpirePrefModel;
use App\Models\LeagueUmpireModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\UmpireEmailSettingsModel;

class DemoController extends Controller
{
    public function demoUmpire()
    {
        if (logged_in_umpire_data()) {
            return redirect('umpire');
        }
        $maxId = UserModel::max('uid');
        $newId = $maxId + 1;

        $email = "demoumpire$newId@yopmail.com";
        $password = $email;

        $user_data = [
            'email' => $email,
            'password' => Hash::make($password),
            'usertype' => 3,

        ];

        if ($user = UserModel::create($user_data)) {
            $umpire_data = [
                'umpid' => $user->uid,
                'name' => "Demo Umpire $newId",
                'phone' => '123-123-1234',
                'zip' => '12345',
                'dob' => '2000-01-01',
                'email_verify_status' => 1,
            ];
            try {
                UmpireModel::create($umpire_data);

                $emailSettings_data = [
                    'umpid' => $user->uid
                ];
                $umpPref_data = [
                    'umpid' => $user->uid,
                    'slno' => 1,
                    'leagueid' => 0
                ];
                UmpireEmailSettingsModel::create($emailSettings_data);
                UmpirePrefModel::create($umpPref_data);
                session(['umpire_data' => $user]);
                return redirect('umpire');
            } catch (Exception $e) {
                UserModel::find($user->uid)->delete();
            }
        }
    }
    public function autoApproveLeagueApplication($id){

        $league_data = LeagueModel::find(1);
        $umpire = UmpireModel::find($id);

        $existingRecord = LeagueUmpireModel::where('umpid', $id)
            ->where('leagueid', $league_data->leagueid)
            ->first();

        if (!$existingRecord) {
            $data = [
                'umpid' => $id,
                'leagueid' => $league_data->leagueid,
                'points' => $league_data->joiningpoint
            ];
            LeagueUmpireModel::create($data);
        }
        $update_data = [
            'status' => 1
        ];

        $umpire->applied_leagues()->where('leagueid', $league_data->leagueid)->update($update_data);

        //adding to umpire pref
        $umpPref_data = [
            'umpid' => $id,
            'slno' => ((int)$umpire->pref()->orderBy('slno', 'DESC')->first()->slno + 1),
            'leagueid' => $league_data->leagueid
        ];
        UmpirePrefModel::create($umpPref_data);
        //adding to umpire pref
        try {
            $msg = 'You joined ' . $league_data->leaguename;
            add_notification($id, $msg, 2, 'ump');
            Session::flash('message', 'Application approved by league.');
        } catch (\Throwable $th) {
        }
    }
    public function demoLeague()
    {
        if (logged_in_league_data()) {
            return redirect('league');
        }
    }
}
