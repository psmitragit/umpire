<?php

namespace App\Http\Controllers;

use App\Models\GameModel;
use Exception;
use App\Models\UserModel;
use App\Models\LeagueModel;
use App\Models\UmpireModel;
use Illuminate\Http\Request;
use App\Models\UmpirePrefModel;
use App\Models\LeagueUmpireModel;
use App\Models\ScheduleLeagueModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\LeagueEmailSettingsModel;
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

                //assigning 2 games to each umpire from old league

                $this->autoApproveLeagueApplication($user->uid, 2);

                $oldGames = GameModel::where('leagueid', 2)->where('gamedate_toDisplay', '<', now())->get();

                $i = 1;
                $game_assign_count = 0;
                $column_to_assign = '';
                $last_assigned_gamedate = '';
                //running the loop 4 times for 4 umpire column
                while ($i <= 4) {
                    $column_to_assign = 'ump' . $i;
                    foreach ($oldGames as $game) {
                        $no_of_umpire = (int)$game->umpreqd;
                        if ($no_of_umpire >= $i) {
                            if ($game->{$column_to_assign} == null) {
                                $gamedate = date('Y-m-d', strtotime($game->gamedate));
                                if ($last_assigned_gamedate !== $gamedate) {
                                    $game->{$column_to_assign} = $user->uid;
                                    $game->save();
                                    $last_assigned_gamedate = $gamedate;
                                    $game_assign_count++;
                                    if ($game_assign_count == 2) {
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }

                //assigning 2 games to each umpire from old league

                session(['umpire_data' => $user]);
                return redirect('umpire');
            } catch (Exception $e) {
                UserModel::find($user->uid)->delete();
            }
        }
    }
    public function autoApproveLeagueApplication($id, $leagueId = 1)
    {

        $league_data = LeagueModel::find($leagueId);
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
        if ($leagueId == 1) {
            try {
                $msg = 'You joined ' . $league_data->leaguename;
                add_notification($id, $msg, 2, 'ump');
                Session::flash('message', 'Application approved by league.');
            } catch (\Throwable $th) {
            }
        }
    }
    public function demoLeague()
    {
        if (logged_in_league_data()) {
            return redirect('league');
        }

        $maxId = UserModel::max('uid');
        $newId = $maxId + 1;

        $email = "demoleague$newId@yopmail.com";
        $password = $email;

        $user_data = [
            'email' => $email,
            'password' => Hash::make($password),
            'isLeagueOwner' => 1,
            'usertype' => 2
        ];
        if ($user = UserModel::create($user_data)) {
            $cc = getUniqueColor();
            $league_data = [
                'name' => "Demo League $newId",
                'phone' => '123-123-1234',
                'leaguename' => "Demo League $newId",
                'status' => 1,
                'cc' => $cc,
            ];
            try {
                $league = LeagueModel::create($league_data);
                $user->leagueid = $league->leagueid;
                $user->save();
                $emailSettings_data = [
                    'leagueid' => $league->leagueid
                ];
                LeagueEmailSettingsModel::create($emailSettings_data);

                $data = [
                    'leagueid' => $league->leagueid,
                    'addless' => '-',
                    'point' => 10
                ];
                LeagueModel::find($league->leagueid)->schedule()->delete();
                ScheduleLeagueModel::create($data);

                //adding demo umpires

                $demoUmpIds = [132, 133, 134, 135, 136, 137, 138, 139, 140];

                foreach ($demoUmpIds as $demoUmpId) {
                    $this->autoApproveLeagueApplication($demoUmpId, $league->leagueid);
                }

                //adding demo umpires

                session(['league_data' => $user]);
                return redirect('league');
            } catch (Exception $e) {
                UserModel::find($user->uid)->delete();
            }
        } else {
        }
    }
}
