<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackMail;
use Exception;
use App\Mail\OTPMail;
use App\Models\GameModel;
use App\Models\UserModel;
use App\Mail\ScheduleGame;
use App\Mail\ResetPassword;
use App\Models\LeagueModel;
use App\Models\UmpireModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UmpirePrefModel;
use App\Models\LeagueUmpireModel;
use App\Models\NotificationModel;
use App\Models\RefundPointsModel;
use Faker\Provider\ar_EG\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\UmpireEmailSettingsModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\VarDumper;

class GeneralController extends Controller
{
    public function index()
    {
        $title = '';
        $data = compact('title');
        return view('general.home')->with($data);
    }
    public function privacy_policy()
    {
        $title = 'Privacy of Policy';
        $data = compact('title');
        return view('general.privacy_policy')->with($data);
    }
    public function terms_of_use()
    {
        $title = 'Terms of Use';
        $data = compact('title');
        return view('general.terms_of_use')->with($data);
    }
    public function forget_password(Request $request)
    {
        $email = $request->email;
        if ($row = UserModel::where('email', $email)->first()) {
            $encid = Crypt::encryptString($row->uid);
            $resetUrl = url('reset-password/' . $encid);
            try {
                Mail::to($request->email)->send(new ResetPassword($resetUrl));
                Session::flash('message', 'Please check your email.');
            } catch (Exception $e) {
                Session::flash('error_message', 'Mail Failed.');
            }
        } else {
            Session::flash('error_message', 'No account found with this email.');
        }
        return redirect()->back();
    }
    public function reset_password($encid)
    {
        $id = Crypt::decryptString($encid);
        $row = UserModel::find($id);
        if ($row) {
            $title = '- Reset Password';
            $data = compact('title', 'id');
            return view('general.reset_password')->with($data);
        }
    }
    public function change_password(Request $request, $id)
    {
        if ($user = UserModel::find($id)) {
            $validator = Validator::make($request->all(), [
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
                ],
            ]);
            if ($validator->fails()) {
                Session::flash('error_message', 'Validation failed.');
                return redirect()->back();
            }
            $user_data = [
                'password' => Hash::make($request->password),
            ];
            if ($user->update($user_data)) {
                Session::flash('message', 'Success');
                if ($user->usertype == 2) {
                    $url = 'league-login';
                } elseif ($user->usertype == 3) {
                    $url = 'umpire-login';
                } elseif ($user->usertype == 1) {
                    $url = 'admin/login';
                }
                return redirect($url);
            } else {
                Session::flash('error_message', 'Something went wrong');
                return redirect()->back();
            }
        }
    }
    public function unsubMail($encMail)
    {
        $email = Crypt::decryptString($encMail);
        $user = UserModel::where('email', $email)->firstOrFail();
        if ($user->usertype == 2) { //league
            $league_data = LeagueModel::find($user->uid);
            $data = [
                'join_game' => 0,
                'leave_game' => 0,
                'apply' => 0,
            ];
            $league_data->email_settings()->update($data);
        } elseif ($user->usertype == 3) { //umpire
            $umpire_data = UmpireModel::find($user->uid);
            $data = [
                'schedule_game' => 0,
                'payment' => 0,
                'message' => 0,
                'application' => 0,
                'cancel_game' => 0,
            ];
            $umpire_data->email_settings()->update($data);
        }
        echo "<script>window.close()</script>";
    }
    public function verifyOTP($id)
    {
        $row = UserModel::find($id);
        if ($row) {
            $title = '- Verify OTP';
            $data = compact('title', 'id');
            return view('general.verify_otp')->with($data);
        }
    }
    public function checkOTP(Request $request, $id)
    {
        $row = UserModel::find($id);
        if ($row) {
            $og_otp = (int)$row->otp;
            $otp = (int)implode('', $request->otp);
            if ($otp == $og_otp) {
                if (UmpireModel::find($id)->update(array('email_verify_status' => 1))) {
                    $emailSettings_data = [
                        'umpid' => $id
                    ];
                    $umpPref_data = [
                        'umpid' => $id,
                        'slno' => 1,
                        'leagueid' => 0
                    ];
                    UmpireEmailSettingsModel::create($emailSettings_data);
                    UmpirePrefModel::create($umpPref_data);
                    Session::flash('message', 'Email Verified.');
                    return redirect('umpire-login');
                } else {
                    Session::flash('error_message', 'Something went wrong.');
                }
            } else {
                Session::flash('error_message', 'Wrong OTP.');
            }
            return redirect()->back();
        }
    }
    public function resendOTP($id)
    {
        $row = UserModel::find($id);
        if ($row) {
            $otp = generateOTP();
            if ($row->update(array('otp' => $otp))) {
                try {
                    Mail::to($row->email)->send(new OTPMail($otp, $id));
                    Session::flash('message', 'Please check your email.');
                } catch (Exception $e) {
                    Session::flash('error_message', 'Something went wrong.');
                }
                Session::flash('error_message', 'Something went wrong.');
            }
            return redirect()->back();
        }
    }

    public function delete_notification($id)
    {
        try {
            $row = NotificationModel::findOrFail($id);
            if (logged_in_league_data()) {
                $uid = logged_in_league_data()->leagueid;
                $col = 'leagueid';
            } elseif (logged_in_umpire_data()) {
                $uid = logged_in_umpire_data()->umpid;
                $col = 'umpid';
            }
            if ((int)$row->{$col} == $uid) {
                $row->delete();
                Session::flash('message', 'Success');
            }
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something went wrong..!!');
        }
        return redirect()->back();
    }

    public function view_admin_change_password($ency_id)
    {
        $admin_id = Crypt::decryptString($ency_id);
        UserModel::where('uid', $admin_id)->where('usertype', 1)->firstOrFail();
        $title = 'Admin Change Password';
        $admin_data = session('admin_data');
        $data = compact('title', 'admin_data');
        return view('admin.change_password')->with($data);
    }

    public function send_feedback(Request $request)
    {
        $request->validate([
            'feedbackSenderName' => 'required',
            'feedbackSenderEmail' => 'required|email',
            'feedback_message' => 'required',
        ]);

        Mail::to(env('ADMIN_EMAIL'))->send(new FeedbackMail($request->input()));
        Session::flash('message', 'Success');
        return redirect()->back();
    }

    //code is running in cronjob============================>>>>##START

    //auto game assign function
    public function game_auto_schedule($leagueId = false, $givenTargetDate = false, $sendNotification = true)
    {
        $umpireColumnsThatWereAssignedPreviously = [];
        // Log the output with a timestamp
        $logMessage = 'This cron(game_auto_schedule) runs at ' . now();
        put_log_msg($logMessage);
        // Log the output with a timestamp
        $leaguesQuery = LeagueModel::where('status', 1);
        if ($leagueId) {
            $leaguesQuery->where('leagueid', $leagueId);
        }
        $leagues = $leaguesQuery->get();

        $games = array();
        $game_ids = array();

        if ($leagues->count() > 0) {
            foreach ($leagues as $league_row) {
                if (!$givenTargetDate) {
                    //getting games from today + {n} days from today based on league settings
                    $targetDate = Carbon::now()->addDays($league_row->assignbefore);
                } else {
                    $targetDate = $givenTargetDate;
                }

                $games = $league_row->games()->whereDate('gamedate', $targetDate)->get();
                if ($games->count() > 0) {
                    foreach ($games as $game_row) {
                        $game_ids[] = $game_row->gameid;
                    }
                }
            }
        }
        //after storing all the game ids from all the leagues on that day
        if (count($game_ids) > 0) {
            $i = 1;
            $column_to_assign = '';
            //running the loop 4 times for 4 umpire column
            $assigned_umpires = [];
            while ($i <= 4) {
                $column_to_assign = 'ump' . $i;
                foreach ($game_ids as $game_id) {
                    $game = GameModel::find($game_id);
                    // saveing prev assigned umpires for manual algo
                    if ($i == 1) {
                        for ($f = 1; $f <= 4; $f++) {
                            if ($game->{"ump$f"}) {
                                $umpireColumnsThatWereAssignedPreviously[$game_id]["ump$f"] = $game->{"ump$f"};
                            }
                        }
                    }
                    // saveing prev assigned umpires for manual algo
                    //no of umpires needed in the game
                    $no_of_umpire = (int)$game->umpreqd;
                    if ($no_of_umpire >= $i) {
                        if ($game->{$column_to_assign} == null) {
                            $league = $game->league;
                            $mainumpage = (int)$league->mainumpage;
                            $otherumpage = (int)$league->otherumpage;
                            $game_date = explode(' ', $game->gamedate)[0];
                            $game_time = substr(explode(' ', $game->gamedate)[1], 0, 5);
                            $game_teams = array($game->hometeamid, $game->awayteamid);
                            $game_divisionsRows = array($game->hometeam->division, $game->awayteam->division);

                            //getting active umpires order by their points
                            $umpires = $league->umpires()
                                ->where('status', 0)
                                ->whereHas('umpire', function ($query) {
                                    $query->where('status', 1);
                                })
                                ->orderBy('points', 'DESC')
                                ->get();

                            $passed_umpire = null; // Initialize a null var to store the umpire that pass the conditions

                            foreach ($umpires as $umpire) {
                                $umpire_row = $umpire->umpire;
                                $umpire_age = (int)get_age($umpire_row->dob);
                                $current_umpire_point = $umpire->points;
                                $condition_met = false; // Initialize a flag to track if any condition has been met for the current umpire

                                // Checking umpire blocked dates and time
                                //due to new changes consider *blocked_dates* as *available_dates*
                                $blocked_dates = $umpire_row->blocked_dates;
                                if (!$blocked_dates->isEmpty()) {
                                    foreach ($blocked_dates as $blocked_date) {
                                        if ($blocked_date->blockdate == $game_date) {
                                            if ($blocked_date->blocktime !== '') {
                                                $blocked_timeArray = explode(',', $blocked_date->blocktime);
                                                if (in_array($game_time, $blocked_timeArray)) {
                                                    $condition_met = false;
                                                    break; // Exit this loop
                                                } else {
                                                    $condition_met = true; // Set the flag to true if time is blocked

                                                }
                                            } else {
                                                $condition_met = false;
                                                break; // Exit this loop
                                            }
                                        } else {
                                            $condition_met = true; // Set the flag to true if time is blocked

                                        }
                                    }
                                } else {
                                    $condition_met = true; // Set the flag to true if time is blocked

                                }

                                // If a condition was met, continue with the next umpire
                                if ($condition_met) {
                                    continue;
                                }

                                // Checking umpire's blocked grounds
                                $blocked_grounds = $umpire_row->blocked_ground;

                                foreach ($blocked_grounds as $blocked_ground) {
                                    if ($blocked_ground->locid == $game->locid) {
                                        $condition_met = true; // Set the flag to true if ground is blocked
                                        break; // Exit this loop
                                    }
                                }

                                // If a condition was met, continue with the next umpire
                                if ($condition_met) {
                                    continue;
                                }

                                // Checking umpire's blocked divisions
                                $blocked_divisions = $umpire_row->blocked_division;
                                if (!empty($game_divisionsRows)) {
                                    $game_divisions = [];
                                    foreach ($game_divisionsRows as $game_divisionsRow) {
                                        if ($game_divisionsRow) {
                                            $game_divisions[] = $game_divisionsRow->id;
                                        }
                                    }
                                    foreach ($blocked_divisions as $blocked_division) {
                                        if (in_array($blocked_division->divid, $game_divisions)) {
                                            $condition_met = true; // Set the flag to true if team is blocked
                                            break; // Exit this loop
                                        }
                                    }
                                }

                                // If a condition was met, continue with the next umpire
                                if ($condition_met) {
                                    continue;
                                }

                                // Checking umpire's blocked teams
                                $blocked_teams = $umpire_row->blocked_team;

                                foreach ($blocked_teams as $blocked_team) {
                                    if (in_array($blocked_team->teamid, $game_teams)) {
                                        $condition_met = true; // Set the flag to true if team is blocked
                                        break; // Exit this loop
                                    }
                                }

                                // If a condition was met, continue with the next umpire
                                if ($condition_met) {
                                    continue;
                                }

                                //checking umpire age
                                $game_player_age = (int)$game->playersage;
                                $age_diff = $umpire_age - $game_player_age;
                                if ($i == 1) {
                                    if ($age_diff < $mainumpage) {
                                        $condition_met = true; // Set the flag to true if age diff is lower
                                    }
                                } else {
                                    if ($age_diff < $otherumpage) {
                                        $condition_met = true; // Set the flag to true if age diff is lower
                                    }
                                }

                                if ($condition_met) {
                                    continue;
                                }

                                //check if already assigned to this game in other position
                                if ($game->ump1 == $umpire->umpid || $game->ump2 == $umpire->umpid || $game->ump3 == $umpire->umpid || $game->ump4 == $umpire->umpid) {
                                    $condition_met = true; // Set the flag to true if already assigned to this game in another position
                                }

                                if ($condition_met) {
                                    continue;
                                }

                                // If no condition was met, assign the umpire to the game
                                if (!$condition_met) {
                                    $passed_umpire = $umpire->umpid;
                                    break;
                                }
                            }

                            // The $passed_umpire var now contains the first umpire that passed all conditions
                            if ($passed_umpire !== null) {
                                $assign_data = [
                                    $column_to_assign => $passed_umpire
                                ];
                                //updateing game table with dynamic umpire column
                                if ($game->update($assign_data)) {
                                    $addLessPointData = addSubPoint($game_id, $passed_umpire, $column_to_assign);
                                    $addLess = $addLessPointData[0];
                                    $point = $addLessPointData[1];
                                    $updated_umpire_point_after_game_assigned = $current_umpire_point + ($addLess . $point);
                                    //updating leagueumpire point
                                    $updated_league_umpire_row_data = [
                                        'points' => $updated_umpire_point_after_game_assigned
                                    ];
                                    if ($umpire->update($updated_league_umpire_row_data)) {
                                        //storing points to a table to refund it after the game completion
                                        $refund_point_data = [
                                            'leagueumpires_id' => $umpire->id,
                                            'game_id' => $game_id,
                                            'addless' => $addLess,
                                            'point' => $point,
                                        ];
                                        RefundPointsModel::create($refund_point_data);
                                        //for the next step
                                        $assigned_umpires[] = $passed_umpire;
                                    }
                                }
                            }
                        }
                    }
                }
                $i++;
            }
            //checking if umpire has multiple games on same datetime if any take actions here
            $this->checksamegames($assigned_umpires, $sendNotification);
            //readjusting umpire positions
            reArrangeUmpiresInGames($game_ids);

            $returnData = compact('umpireColumnsThatWereAssignedPreviously', 'game_ids');
            // dd($returnData);
            return $returnData;
        }
    }
    public function checksamegames($assigned_umpires, $sendNotification)
    {
        $uniqueumpireids = array_unique($assigned_umpires);
        if (count($uniqueumpireids) > 0) {
            foreach ($uniqueumpireids as $assigned_umpire_id) {
                $assigned_umpire_row = UmpireModel::find($assigned_umpire_id);
                $umpire_prefs = $assigned_umpire_row->pref()->orderBy('slno', 'ASC')->get();
                foreach ($umpire_prefs as $umpire_pref) {
                    $pref[] = $umpire_pref->leagueid;
                }
                $games_row = GameModel::selectRaw("*,
                CASE
                    WHEN ump1 = $assigned_umpire_id THEN 'ump1'
                    WHEN ump2 = $assigned_umpire_id THEN 'ump2'
                    WHEN ump3 = $assigned_umpire_id THEN 'ump3'
                    WHEN ump4 = $assigned_umpire_id THEN 'ump4'
                END AS ump")
                    ->where(function ($query) use ($assigned_umpire_id) {
                        $query->orWhere('ump1', $assigned_umpire_id)
                            ->orWhere('ump2', $assigned_umpire_id)
                            ->orWhere('ump3', $assigned_umpire_id)
                            ->orWhere('ump4', $assigned_umpire_id);
                    })
                    ->where('gamedate', '>=', now())
                    ->get();
                if ($games_row->count() > 0) {
                    $game_data_groupedBy_date = [];
                    foreach ($games_row as $gm_row) {
                        if ($gm_row->ump == 'ump1') {
                            $game_pay = $gm_row->ump1pay + $gm_row->ump1bonus;
                        } else {
                            $game_pay = $gm_row->ump234pay + $gm_row->ump234bonus;
                        }
                        $game_data_groupedBy_date[explode(' ', $gm_row->gamedate)[0]][] = [
                            'id' => $gm_row->gameid,
                            'leagueid' => $gm_row->leagueid,
                            'pay' => $game_pay,
                            'ump' => $gm_row->ump
                        ];
                    }

                    $filteredGame = []; // Variable to store the filtered values
                    $removedGames = [];  // Variable to store the removed values


                    foreach ($game_data_groupedBy_date as $k => &$samedategame) {
                        if (count($samedategame) > 1) {
                            // Sort the games based on user preferences
                            usort($samedategame, function ($a, $b) use ($pref) {
                                $prefA = $pref[0]; // 1st preference

                                // Check if the 1st preference is 0
                                if ($prefA === 0) {
                                    if ($a['pay'] != $b['pay']) {
                                        return $b['pay'] - $a['pay']; // Sort by pay descending
                                    } else {
                                        return 0; // Pay is the same, move to the 2nd preference
                                    }
                                } else {
                                    // 1st preference is a league ID (not 0)
                                    // Check if the league IDs are the same
                                    if ($a['leagueid'] == $prefA && $b['leagueid'] == $prefA) {
                                        if ($a['pay'] != $b['pay']) {
                                            return $b['pay'] - $a['pay']; // Sort by pay descending for the same league ID
                                        } else {
                                            return 0; // Pay is the same for the same league ID
                                        }
                                    } elseif ($a['leagueid'] == $prefA) {
                                        return -1; // $a is the 1st preference league ID
                                    } elseif ($b['leagueid'] == $prefA) {
                                        return 1; // $b is the 1st preference league ID
                                    } else {
                                        return 0; // Neither is the 1st preference league ID
                                    }
                                }
                            });

                            // Keep the first game and remove the rest
                            $filteredGame = $samedategame[0];
                            // Remove the rest of the games and add them to the removedGames variable
                            $removedGames = array_merge($removedGames, array_slice($samedategame, 1));

                            //removing the umpire slots from the same timed games
                            if (count($removedGames) > 0) {
                                foreach ($removedGames as $removedGame) {
                                    $remove_game_row = GameModel::find($removedGame['id']);
                                    $remove_game_updated_data = [
                                        $removedGame['ump'] => null
                                    ];
                                    if ($remove_game_row->update($remove_game_updated_data)) {
                                        $leagueumpire_row = $assigned_umpire_row->leagues()->where('leagueid', $removedGame['leagueid'])->first();
                                        //refunding the points that were cut during the auto assigning
                                        refund_point_to_Aumpire($leagueumpire_row, $removedGame['id']);
                                    }
                                }
                            }
                        }
                        $assigned_game = $samedategame[0];
                    }
                    if ($sendNotification) {
                        $league = LeagueModel::find($assigned_game['leagueid']);
                        $assigned_game_row = GameModel::find($assigned_game['id']);
                        try {
                            //notification mail
                            if ($assigned_umpire_row->email_settings->schedule_game == 1) {
                                $umpire_email = $assigned_umpire_row->user->email;
                                Mail::to($umpire_email)->send(new ScheduleGame($league, $assigned_umpire_row, $assigned_game_row, 'ump', $umpire_email));
                            }
                            if ($league->email_settings->join_game == 1) {
                                foreach ($league->users as $league_admin) {
                                    $league_admin_email = $league_admin->email;
                                    Mail::to($league_admin_email)->send(new ScheduleGame($league, $assigned_umpire_row, $assigned_game_row, 'league', $league_admin_email));
                                }
                            }
                            //notification mail end
                        } catch (\Throwable $th) {
                        }
                        $msg = 'New game assigned on ' . date('D m/d/y', strtotime($assigned_game_row->gamedate));
                        $msg2 = $assigned_umpire_row->name . ' assigned to a game on ' . date('D m/d/y', strtotime($assigned_game_row->gamedate));
                        add_notification($assigned_umpire_row->umpid, $msg, 4, 'ump');
                        add_notification($assigned_game['leagueid'], $msg2, 4, 'league');
                    }
                }
            }
        }
    }

    //after game function
    public function afterGame()
    {
        // Log the output with a timestamp
        $logMessage = 'This cron(afterGame) runs at ' . now();
        put_log_msg($logMessage);
        // Log the output with a timestamp

        $past_games = GameModel::where('gamedate_toDisplay', '<', now())
            ->where('status', 0)
            ->get();
        // dd($past_games);
        if ($past_games->count() > 0) {
            foreach ($past_games as $past_game) {
                $past_game->report == 0 ? $flag = true : $flag = false;
                $reportNAN = 0;
                $paidUmpires = $past_game->paid_umpires;
                $paidUmpIds = [];
                $allUmpiresIds = [];
                if (!empty($paidUmpires)) {
                    $paidUmpIds = explode(',', $paidUmpires);
                }
                for ($i = 1; $i <= 4; $i++) {
                    $col = 'ump' . $i;
                    $reportCol = 'report' . $i;
                    $umpid = $past_game->{$col};
                    $owed = 0;
                    if ($umpid !== null) {
                        $allUmpiresIds[] = $umpid;
                        $umpire = UmpireModel::findOrFail($umpid);
                        if (!$flag) {
                            if ($past_game->{$reportCol} !== null) {
                                $umpFlag = true;
                            } else {
                                $umpFlag = false;
                                $reportNAN++;
                            }
                        } else {
                            $umpFlag = true;
                        }
                        if (!in_array($umpid, $paidUmpIds)) {
                            if ($umpFlag) { //if report is given or no report needed thn only proceed to payment
                                $leagueumpire = $umpire->leagues()->where('leagueid', $past_game->leagueid)->first();
                                refund_point_to_Aumpire($leagueumpire, $past_game->gameid);
                                $owed = $leagueumpire->owed ?? 0;
                                if ($col == 'ump1') {
                                    $pay = $past_game->ump1pay + (float)$past_game->ump1bonus;
                                } else {
                                    $pay = $past_game->ump234pay + (float)$past_game->ump234bonus;
                                }
                                $profilePay = $leagueumpire->payout ?? 0;
                                //if profile pay is higher thn the game payout thn give umpire the  highest pay
                                if ($profilePay > $pay) {
                                    $pay = $profilePay;
                                }
                                $owed += $pay;

                                //saving the owe
                                $leagueumpire->owed = $owed;
                                if ($leagueumpire->save()) {
                                    if (add_payRecord($leagueumpire->leagueid, $leagueumpire->umpid, date('Y-m-d', strtotime($past_game->gamedate_toDisplay)), $pay, 'game', $past_game->gameid)) {
                                        $past_game->paid_umpires .= ',' . $umpid;
                                        $past_game->paid_umpires = ltrim($past_game->paid_umpires, ',');
                                        array_push($paidUmpIds, $umpid);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($reportNAN == 0) {
                    sort($allUmpiresIds);
                    sort($paidUmpIds);
                    if ($allUmpiresIds == $paidUmpIds) {
                        $past_game->status = 1;
                    }
                }
                $past_game->save();
            }
        }
    }
    //code is running in cronjob============================>>>>##END
    public function test()
    {
    }
}
