<?php

use App\Models\CMS;
use App\Mail\Payment;
use App\Models\GameModel;
use App\Models\TeamModel;
use App\Models\UserModel;
use App\Models\LeagueModel;
use App\Models\PayoutModel;
use App\Models\UmpireModel;
use App\Models\SiteMetaData;
use App\Models\ApplyToLeague;
use App\Models\LocationModel;
use App\Models\ToggleSettings;
use Illuminate\Support\Carbon;
use App\Models\AbsentReportModel;
use App\Models\LeagueUmpireModel;
use App\Models\NotificationModel;
use App\Models\RefundPointsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\HighlightedReportModel;
use App\Http\Controllers\LeagueController;

function stagingMark()
{
    if (env('APP_ENV') !== 'production') {
        echo '
        <div style="position: absolute; top: 50px; left: -50px; z-index: 99999;">
        <img src="' . asset('storage/images/staging.png') . '" alt="" style="transform: rotate(-50deg) scale(1.2); width: 200px; height: auto;">
    </div>
        ';
    }
}
function generateOTP()
{
    $otp = '';
    for ($i = 0; $i < 6; $i++) {
        $otp .= mt_rand(0, 9);
    }
    return (int)$otp;
}
function generateRandomColor()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
function getUniqueColor()
{
    $colorCode = generateRandomColor();
    $exists = DB::table('leagues')->where('cc', $colorCode)->exists();
    if ($exists) {
        return getUniqueColor();
    }
    return $colorCode;
}
function logged_in_admin_data()
{
    $data = session('admin_data');
    $id = $data->uid;
    $return = UserModel::find($id);
    return $return;
}
function logged_in_league_data()
{
    try {
        $data = session('league_data');
        $id = $data->leagueid;
        $return = LeagueModel::find($id);
        return $return;
    } catch (\Throwable $th) {
        return false;
    }
}
function logged_in_league_admin_data()
{
    try {
        $data = session('league_data');
        $id = $data->uid;
        $return = UserModel::find($id);
        return $return;
    } catch (\Throwable $th) {
        return false;
    }
}
function logged_in_umpire_data()
{
    try {
        $data = session('umpire_data');
        $id = $data->uid;
        $return = UmpireModel::find($id);
        return $return;
    } catch (\Throwable $th) {
        return false;
    }
}
function location_select_box($userType, $selected_id = null)
{
    $select_box = '<select name="locid[]" id="" class="sel2" required>';
    if ($userType == 'admin') {
        $locations = LocationModel::get();
    } elseif ($userType == 'league') {
        $locations = logged_in_league_data()->location;
    }
    if (!$locations->isEmpty()) :
        foreach ($locations as $location) :
            $selected = '';
            if ($selected_id !== null && $selected_id == $location->locid) {
                $selected = 'selected';
            }
            $select_box .= '<option ' . $selected . ' value="' . $location->locid . '">' . addslashes($location->ground) . '</option>';
        endforeach;
    endif;
    $select_box .= '</select>';
    return $select_box;
}
function generateHourSelectBox($name, $selected_hour = null)
{
    $select_box = "<select name=\"$name\" required class=\"small-select selector-names\">";
    for ($hour = 1; $hour <= 24; $hour++) {
        $selected = '';
        if ($selected_hour !== null && $selected_hour == $hour) {
            $selected = 'selected';
        }
        $hourLabel = ($hour <= 12) ? ($hour == 12 ? "12 PM" : "{$hour} AM") : (($hour == 24) ? "12 AM" : ($hour - 12) . " PM");
        $select_box .= "<option $selected value=\"$hour\">$hourLabel</option>";
    }
    $select_box .= "</select>";
    return $select_box;
}

function count_avg_league_games_per_week($league_id)
{
    $games = GameModel::where('leagueid', $league_id)->get();
    $gamesByWeek = $games->groupBy(function ($game) {
        $date = $game->gamedate;
        $week = date('Y-W', strtotime($date));
        return $week;
    });
    $averageGamesByWeek = round(
        $gamesByWeek
            ->map(function ($gamesInWeek) {
                return $gamesInWeek->count();
            })
            ->avg(),
        0,
    );
    return $averageGamesByWeek;
}

function checkToggleStatus($league, $type)
{
    return ToggleSettings::where('toggled_for', $league)->where('setting', $type)->first();
}
function getCMSContent($page, $section)
{
    return @CMS::where('page', $page)->where('section', $section)->first()->value;
}
function getFAQ($section, $type)
{
    $row = CMS::where('page', 'faq')->where('section', $section)->first();
    if ($row) {
        $value = json_decode($row->value, true);
        return $value[$type];
    }
}

function toggleSettings($league, $type, $status, $toggled_by = 0)
{
    $row = checkToggleStatus($league, $type);
    $leagueRow = LeagueModel::find($league);

    if ($row) {
        if (!$status) {
            if ($toggled_by !== 0) {
                if ($row->toggled_by == $toggled_by) {
                    $row->delete();
                } else {
                    return false;
                }
            } else {
                $row->delete();
            }
            return true;
        }
    } else {
        if ($status) {
            ToggleSettings::create(['toggled_by' => $toggled_by, 'setting' => $type, 'toggled_for' => $league]);
            if ($type == 'age') {
                $leagueRow->mainumpage = 0;
                $leagueRow->otherumpage = 0;

                $leagueRow->save();
                $leagueRow->age_of_players()->delete();
            } elseif ($type == 'divisions') {
                foreach ($leagueRow->divisions as $division) {
                    $division->blockedDivisions()->delete();
                }
            } elseif ($type == 'auto_scheduler') {
                $leagueRow->age_of_players()->delete();
                $leagueRow->locations()->delete();
                $leagueRow->pay()->delete();
                $leagueRow->time()->delete();
                $leagueRow->day_of_week()->delete();
                $leagueRow->umpire_position()->delete();
                $leagueRow->umpire_duration()->delete();
                $leagueRow->total_game()->delete();
            } elseif ($type == 'teams') {
                $leagueRow->blocked_umpire_teams()->delete();
            }
            return true;
        }
    }
}

function getFirstBlankTeam()
{
    return TeamModel::where('leagueid', 0)->orderBy('teamid', 'ASC')->first();
}

function getSecondBlankTeam()
{
    return TeamModel::where('leagueid', 0)->orderBy('teamid', 'DESC')->first();
}

function count_avg_league_games_pay_per_week($league_id, $details = false)
{
    $games = GameModel::where('leagueid', $league_id)->get();
    $gamesByWeek = $games->groupBy(function ($game) {
        $date = $game->gamedate;
        $week = date('Y-W', strtotime($date));
        return $week;
    });

    $sumUmp1Pay = $gamesByWeek->map(function ($gamesInWeek) {
        return $gamesInWeek->sum('ump1pay');
    });

    $sumUmp1Bonus = $gamesByWeek->map(function ($gamesInWeek) {
        return $gamesInWeek->sum('ump1bonus');
    });

    $sumUmp234Pay = $gamesByWeek->map(function ($gamesInWeek) {
        return $gamesInWeek->sum('ump234pay');
    });

    $sumUmp234Bonus = $gamesByWeek->map(function ($gamesInWeek) {
        return $gamesInWeek->sum('ump234bonus');
    });


    $averageUmp1Pay = round($sumUmp1Pay->map(function ($sum) use ($gamesByWeek) {
        return $sum / $gamesByWeek->count();
    })->avg(), 0);

    $averageUmp1Bonus = round($sumUmp1Bonus->map(function ($sum) use ($gamesByWeek) {
        return $sum / $gamesByWeek->count();
    })->avg(), 0);

    $averageUmp234Pay = round($sumUmp234Pay->map(function ($sum) use ($gamesByWeek) {
        return $sum / $gamesByWeek->count();
    })->avg(), 0);

    $averageUmp234Bonus = round($sumUmp234Bonus->map(function ($sum) use ($gamesByWeek) {
        return $sum / $gamesByWeek->count();
    })->avg(), 0);

    if ($details) {
        $return = compact('averageUmp1Pay', 'averageUmp1Bonus', 'averageUmp234Pay', 'averageUmp234Bonus');
    } else {
        $return = ($averageUmp1Pay + $averageUmp1Bonus + $averageUmp234Pay + $averageUmp234Bonus);
    }
    return $return;
}

function check_game_slot_status($league_id)
{
    $league = LeagueModel::find($league_id);
    $games = $league->games;

    $status = '<span class="text-danger">Close</span>';
    if ($games->count() > 0) {
        foreach ($games as $game) {
            if ($game->umpreqd == 1) {
                if ($game->ump1 == null) {
                    $status = '<span class="text-success">Open</span>';
                }
            } elseif ($game->umpreqd == 2) {
                if ($game->ump1 == null || $game->ump2 == null) {
                    $status = '<span class="text-success">Open</span>';
                }
            } elseif ($game->umpreqd == 3) {
                if ($game->ump1 == null || $game->ump2 == null || $game->ump3 == null) {
                    $status = '<span class="text-success">Open</span>';
                }
            } elseif ($game->umpreqd == 4) {
                if ($game->ump1 == null || $game->ump2 == null || $game->ump3 == null || $game->ump4 == null) {
                    $status = '<span class="text-success">Open</span>';
                }
            }
        }
    }
    return $status;
}
function league_new_applicant_count($league_id)
{
    $rowCount = LeagueModel::find($league_id)->umpire_apply()
        ->whereIn('status', [0, 3])
        ->get()
        ->count();
    return $rowCount;
}
function displayCalendar($month, $year, $cal_data = array())
{
    $today = Carbon::now();

    $date = new DateTime("$year-$month-01");

    $numDays = $date->format('t');

    $firstDay = $date->format('N');

    $currentDay = 1;

    echo "<table class='table-responsive-sm' id='calendar'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Sun</th>";
    echo "<th>Mon</th>";
    echo "<th>Tue</th>";
    echo "<th>Wed</th>";
    echo "<th>Thu</th>";
    echo "<th>Fri</th>";
    echo "<th>Sat</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    echo "<tr>";
    for ($i = 1; $i <= $firstDay; $i++) {
        echo "<td></td>";
        $currentDay++;
    }
    for ($i = 1; $i <= $numDays; $i++) {

        $currentDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);

        if ($currentDay == 8) {
            echo "</tr><tr>";
            $currentDay = 1;
        }

        if (!isset($cal_data[$currentDate])) {
            $class = "not-avail-date";
            $class_nombs = "white-able";
            $text_abl = "Make Available";
        } else {
            $calender_data = $cal_data[$currentDate];

            if ($calender_data !== '' && !is_array($calender_data)) {
                $class = "par-avlbe text-white par-avail-date";
                $text_abl = "Partial Available";
                $class_nombs = "orng-able";
            } elseif (is_array($calender_data)) {
                $class = "bg-primary text-white game-date";
                $class_nombs = "innerpardiv";
                $text_abl = '';
                foreach ($calender_data as $cdata) {
                    $cancel_text = '';
                    $game_row = GameModel::find($cdata['gameid']);
                    $location = $game_row->location->ground;
                    $game_date = explode(' ', $game_row->gamedate)[0];
                    $time = date('h:i A', strtotime($game_row->gamedate_toDisplay));
                    $cancelbefore = (int)$game_row->league->leavebefore;

                    $initialDate = Carbon::parse($game_date);
                    $modifiedDate = $initialDate->subDays($cancelbefore);

                    $daysDifference = $today->diffInDays($modifiedDate);

                    if ($modifiedDate->isSameDay($today) || $modifiedDate->greaterThan($today)) {
                        $cancel_text .= '<div><a onclick="demoWarning();" href="javascript:;">';

                        if ($daysDifference == 0) {
                            $cancel_text .= "Cancel within today";
                        } elseif ($daysDifference == 1) {
                            $cancel_text .= "Cancel within $daysDifference day";
                        } else {
                            $cancel_text .= "Cancel within $daysDifference days";
                        }
                        $cancel_text .= '</a></div>';
                    }
                    $text_abl .= "
                <div>$location</div>
                <div class='color-cona'><i class='fa-solid fa-clock'></i>  $time</div>
                <div class='text-danger mt-2'>$cancel_text</div>
                ";
                }
            } else {
                $class = "bg-successs text-white avail-date";
                $text_abl = "Make Not Available";
                $class_nombs = "green-able";
            }
        }
        echo "<td><div class='innerpardiv " . $class_nombs . " '>" . $text_abl . " </div><span data-date='$currentDate' class='cal-dates $class'>$i</span></td>";
        $currentDay++;
    }

    echo "</tr>";
    echo "</tbody>";
    echo "</table>";
}
function get_next_month($year, $month)
{
    $next_month = $month + 1;
    $next_year = $year;
    if ($month >= 12) {
        $next_month = 1;
        $next_year++;
    }
    return compact('next_month', 'next_year');
}
function get_prev_month($year, $month)
{
    $prev_month = $month - 1;
    $prev_year = $year;
    if ($month <= 1) {
        $prev_month = 12;
        $prev_year--;
    }
    return compact('prev_month', 'prev_year');
}
function get_age($dob)
{

    $birthDate = new DateTime($dob);

    $currentDate = new DateTime();

    $age = $currentDate->diff($birthDate)->y;

    return $age;
}
function checkIfReportIsHighlighted($gameid, $report_col)
{
    return HighlightedReportModel::where('gameid', $gameid)->where('report_col', $report_col)->first();
}
function checkIfReportIsFake($gameid, $report_col)
{
    return AbsentReportModel::where('gameid', $gameid)->where('report_col', $report_col)->first();
}
function reportFake($gameid, $report_col, $umpid)
{
    $game = GameModel::find($gameid);
    $paidUmpires = $game->paid_umpires;
    $paidUmpIds = [];
    if (!empty($paidUmpires)) {
        $paidUmpIds = explode(',', $paidUmpires);
    }
    if (in_array($umpid, $paidUmpIds)) {
        // deduct
        $payRecordRow = PayoutModel::where('gameid', $gameid)->where('umpid', $umpid)->first();
        $leagueController = new LeagueController();
        $leagueController->delete_payout($payRecordRow->id);
    }
    $data = [
        'gameid' => $gameid,
        'umpid' => $umpid,
        'report_col' => $report_col,
    ];
    AbsentReportModel::create($data);
}
function add_payRecord($leagueid, $umpid, $paydate, $payamt, $pmttype, $gameid = null)
{
    try {
        $leagueumpire = LeagueUmpireModel::where('leagueid', $leagueid)
            ->where('umpid', $umpid)->first();
        $umpire = $leagueumpire->umpire;
        $league = $leagueumpire->league;
        $owe = $leagueumpire->owed ?? 0;
        $ump_pending = getUmpireOweReceived($umpid)['total_pending'];
        $payout_data = [
            'leagueid' => $leagueid,
            'umpid' => $umpid,
            'paydate' => $paydate,
            'payamt' => $payamt,
            'pmttype' => $pmttype,
            'gameid' => $gameid,
            'owe' => $owe,
            'ump_pending' => $ump_pending,
        ];
        PayoutModel::create($payout_data);
        try {
            if ($pmttype !== 'game' && $pmttype !== 'adjusted') {
                //notification mail
                if ($umpire->email_settings->payment == 1) {
                    $umpire_email = $umpire->user->email;
                    Mail::to($umpire_email)->send(new Payment($league, $umpire, $payout_data, $umpire_email));
                }
                //notification mail end
            }
        } catch (\Throwable $th) {
        }
        if ($pmttype == 'game' || $pmttype == 'adjusted') {
            $msg = '$' . $payamt . ' added to your wallet.';
        } elseif ($pmttype == 'payout') {
            $msg = 'Received amount $' . $payamt . ' on ' . date('D m/d/y', strtotime($paydate));
        } elseif ($pmttype == 'bonus') {
            $msg = 'Received bonus amount $' . $payamt . ' on ' . date('D m/d/y', strtotime($paydate));
        }
        add_notification($umpid, $msg, 3, 'ump');
        return true;
    } catch (\Throwable $th) {
        return false;
    }
}
function check_if_within_umpires_blocked_time($time, $umpid)
{
    $umpire = UmpireModel::find($umpid);
    $blocked_times = $umpire->blocked_dates;
    if ($blocked_times->count() > 0) {
        foreach ($blocked_times as $blocked_time) {
            if ($blocked_time->blocktime == '') {
                if (date('Y-m-d', strtotime($time)) == $blocked_time->blockdate) {
                    return true;
                }
            } else {
                $timeArray = explode(',', $blocked_time->blocktime);
                foreach ($timeArray as $time) {
                    if ($time == $blocked_time->blockdate . ' ' . $time . ':00') {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}
function refund_point_to_Aumpire($leagueumpire_row, $game_id)
{
    try {
        $refund_game_instance = RefundPointsModel::where('game_id', $game_id)->where('leagueumpires_id', $leagueumpire_row->id);
        $refund_game = $refund_game_instance->first();
        if ($refund_game !== null) {
            $current_point = (int)$leagueumpire_row->points;
            if ($refund_game->addless == '-') {
                $updated_point = $current_point + (int)$refund_game->point;
            } else {
                $updated_point = $current_point - (int)$refund_game->point;
            }
            $update_leagueumpire_data = [
                'points' => $updated_point
            ];
            if ($leagueumpire_row->update($update_leagueumpire_data)) {
                $refund_game_instance->delete();
                return true;
            }
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        return false;
    }
}
function get_umpire_games_count_in_a_league($umpid, $leagueid, $currently_assigned = false)
{
    $res = GameModel::where(function ($query) use ($umpid) {
        $query->orWhere('ump1', $umpid)
            ->orWhere('ump2', $umpid)
            ->orWhere('ump3', $umpid)
            ->orWhere('ump4', $umpid);
    })
        ->when($leagueid !== null, function ($query) use ($leagueid) {
            $query->where('leagueid', $leagueid);
        })
        ->when($currently_assigned == true, function ($query) {
            $query->where('gamedate', '>=', now());
        })
        ->orderBy('gamedate', 'ASC')
        ->get();

    return $res->count();
}
function get_umpire_games($umpid)
{
    $res = GameModel::where(function ($query) use ($umpid) {
        $query->orWhere('ump1', $umpid)
            ->orWhere('ump2', $umpid)
            ->orWhere('ump3', $umpid)
            ->orWhere('ump4', $umpid);
    })->get();
    if ($res->count() > 0) {
        return $res;
    } else {
        return false;
    }
}
function umpire_upcoming_games($limit = false)
{
    $umpire_data = logged_in_umpire_data();
    $umpid = $umpire_data->umpid;

    $query = GameModel::where(function ($query) use ($umpid) {
        $query->orWhere('ump1', $umpid)
            ->orWhere('ump2', $umpid)
            ->orWhere('ump3', $umpid)
            ->orWhere('ump4', $umpid);
    })
        ->where('gamedate_toDisplay', '>=', now())
        ->orderBy('gamedate_toDisplay', 'ASC');

    if ($limit !== false) {
        $query->limit($limit);
    }

    $umpire_upcoming_games = $query->get();

    if ($umpire_upcoming_games->count() > 0) {
        return $umpire_upcoming_games;
    } else {
        return false;
    }
}
function league_upcoming_games($limit = false)
{
    $league_data = logged_in_league_data();
    $leagueid = $league_data->leagueid;

    $query = GameModel::where('leagueid', $leagueid)
        ->where('gamedate_toDisplay', '>=', now())
        ->orderBy('gamedate_toDisplay', 'ASC');

    if ($limit !== false) {
        $query->limit($limit);
    }

    $league_upcoming_games = $query->get();

    if ($league_upcoming_games->count() > 0) {
        return $league_upcoming_games;
    } else {
        return false;
    }
}
function addSubPoint($game_id, $umpire_id, $pos)
{
    $game = GameModel::findOrFail($game_id);
    $league = $game->league;
    $umpire = UmpireModel::findOrFail($umpire_id);
    $leagueUmpire = $umpire->leagues()->where('leagueid', $game->leagueid)->first();

    if ($pos == 'ump1') {
        $pay = $game->ump1pay + $game->ump1bonus;
        $pos = 1;
    } else {
        $pay = $game->ump234pay + $game->ump234bonus;
        $pos = 2;
    }

    $point = $league->schedule[0]->addless . $league->schedule[0]->point;

    $player_age = (int)$game->playersage;
    //adjust point based on age group
    $league_age_settings = $league->age_of_players;
    if (!$league_age_settings->isEmpty()) {
        foreach ($league_age_settings as $league_age_setting) {
            $from = (int)$league_age_setting->from;
            $to = (int)$league_age_setting->to;
            if ($player_age >= $from && $player_age <= $to) {
                $point = $point + ($league_age_setting->addless . $league_age_setting->point);
                break;
            }
        }
    }
    //adjust point based on location
    $league_location_settings = $league->locations;
    if (!$league_location_settings->isEmpty()) {
        foreach ($league_location_settings as $league_location_setting) {
            if ($league_location_setting->locid == $game->locid) {
                $point = $point + ($league_location_setting->addless . $league_location_setting->point);
                break;
            }
        }
    }
    //adjust point based on pay
    $pay = (int)$pay;
    $league_pay_settings = $league->pay;
    if (!$league_pay_settings->isEmpty()) {
        foreach ($league_pay_settings as $league_pay_setting) {
            $from = (int)$league_pay_setting->from;
            $to = (int)$league_pay_setting->to;
            if ($pay >= $from && $pay <= $to) {
                $point = $point + ($league_pay_setting->addless . $league_pay_setting->point);
                break;
            }
        }
    }
    //adjust point based on time
    $carbonDate = Carbon::parse($game->gamedate);
    $gameHour = (int)$carbonDate->format('H');
    $league_time_settings = $league->time;
    if (!$league_time_settings->isEmpty()) {
        foreach ($league_time_settings as $league_time_setting) {
            $from = (int)$league_time_setting->from;
            $to = (int)$league_time_setting->to;
            if ($gameHour >= $from && $gameHour <= $to) {
                $point = $point + ($league_time_setting->addless . $league_time_setting->point);
                break;
            }
        }
    }
    //adjust point based on day
    $carbonDate2 = Carbon::parse($game->gamedate);
    $gameDay = strtoupper($carbonDate2->format('D'));
    $league_day_settings = $league->day_of_week;
    if (!$league_day_settings->isEmpty()) {
        foreach ($league_day_settings as $league_day_setting) {
            if ($league_day_setting->dayname == $gameDay) {
                $point = $point + ($league_day_setting->addless . $league_day_setting->point);
                break;
            }
        }
    }
    //adjust point based on position
    $league_pos_settings = $league->umpire_position;
    if (!$league_pos_settings->isEmpty()) {
        foreach ($league_pos_settings as $league_pos_setting) {
            if ((int)$league_pos_setting->position == $pos) {
                $point = $point + ($league_pos_setting->addless . $league_pos_setting->point);
                break;
            }
        }
    }
    //adjust point based on umpire duration
    $createdAt = $leagueUmpire->created_at;
    $currentDate = Carbon::now();
    $daysDifference = (int)$currentDate->diffInDays($createdAt);
    $league_duration_settings = $league->umpire_duration;
    if (!$league_duration_settings->isEmpty()) {
        foreach ($league_duration_settings as $league_duration_setting) {
            $from = (int)$league_duration_setting->from;
            $to = (int)$league_duration_setting->to;
            if ($daysDifference >= $from && $daysDifference <= $to) {
                $point = $point + ($league_duration_setting->addless . $league_duration_setting->point);
                break;
            }
        }
    }
    //adjust point based on total games
    $total_games = get_umpire_games_count_in_a_league($umpire_id, $league->leagueid);
    $league_total_game_settings = $league->total_game;
    if (!$league_total_game_settings->isEmpty()) {
        foreach ($league_total_game_settings as $league_total_game_setting) {
            $from = (int)$league_total_game_setting->from;
            $to = (int)$league_total_game_setting->to;
            if ($total_games >= $from && $total_games <= $to) {
                $point = $point + ($league_total_game_setting->addless . $league_total_game_setting->point);
                break;
            }
        }
    }

    if ($point >= 0) {
        $return = ['+', $point];
    } else {
        $return = ['-', abs($point)];
    }
    return $return;
}
function getUmpireOweReceived($umpid)
{
    $umpire_data = UmpireModel::find($umpid);
    $leagues = $umpire_data->leagues;
    $total_pending = 0;
    $total_received = 0;
    if (!$leagues->isEmpty()) {
        foreach ($leagues as $league) {
            $total_pending += $league->owed ?? 0;
            $total_received += ($league->received ?? 0) + ($league->bonus ?? 0);
        }
    }
    return compact('total_pending', 'total_received');
}
function add_notification($userid, $msg, $iconid, $usertype)
{
    try {
        $idcol = $usertype . 'id';
        $msgcol = $usertype . 'msg';
        $notitype = 0;

        $data = [
            $idcol => $userid,
            $msgcol => $msg,
            'type' => $notitype,
            'iconid' => $iconid,
        ];

        NotificationModel::create($data);
    } catch (\Throwable $th) {
        return false;
    }
}
function checkIfUmpireNeedsToSubmitReport()
{
    $umpire = logged_in_umpire_data();
    $umpid = $umpire->umpid;
    return GameModel::where(function ($query) use ($umpid) {
        $query->orWhere('ump1', $umpid)
            ->orWhere('ump2', $umpid)
            ->orWhere('ump3', $umpid)
            ->orWhere('ump4', $umpid);
    })
        ->where('gamedate_toDisplay', '<', now())
        ->whereRaw('CASE
        WHEN ump1 = ? THEN report1
        WHEN ump2 = ? THEN report2
        WHEN ump3 = ? THEN report3
        WHEN ump4 = ? THEN report4
    END IS NULL', [$umpid, $umpid, $umpid, $umpid])
        ->leftJoin('absent_report', function ($join) use ($umpid) {
            $join->on('games.gameid', '=', 'absent_report.gameid')
                ->whereRaw("
                (CASE
                    WHEN ump1 = $umpid THEN 'report1'
                    WHEN ump2 = $umpid THEN 'report2'
                    WHEN ump3 = $umpid THEN 'report3'
                    WHEN ump4 = $umpid THEN 'report4'
                END) = absent_report.report_col
            ");
        })
        ->whereNull('absent_report.id')
        ->get()->count();
}
function get_notifications($userid, $usertype, $limit = false)
{
    try {
        if ($usertype == 2) {
            $league = LeagueModel::find($userid);
            $return = $league->notifications()
                ->where('type', 0)
                ->when($limit !== false, function ($query) use ($limit) {
                    return $query->limit($limit);
                })
                ->get();
        } elseif ($usertype == 3) {
            $umpire_data = UmpireModel::find($userid);
            $leagues = $umpire_data->leagues;
            $leagueids = [];
            if (!$leagues->isEmpty()) {
                foreach ($leagues as $league) {
                    $leagueids[] = $league->leagueid;
                }
            }
            $query = NotificationModel::where(function ($query) use ($leagueids) {
                $query->whereIn('leagueid', $leagueids)
                    ->where('type', 1);
            })
                ->orWhere('umpid', $umpire_data->umpid)
                ->select(DB::raw("*, COALESCE(leaguemsg, umpmsg) as msg"))
                ->orderBy('created_at', 'DESC');
            if ($limit) {
                $query->limit($limit);
            }
            $return = $query->get();
        }
        return $return;
    } catch (\Throwable $th) {
        return false;
    }
}
function getMetaValue($meta_key, $row = false)
{
    if ($row) {
        return SiteMetaData::where('meta_key', $meta_key)->first();
    } else {
        return SiteMetaData::where('meta_key', $meta_key)->first()->meta_value;
    }
}
function put_log_msg($logMessage)
{
    $logPath = storage_path('logs/cronLog.txt');
    file_put_contents($logPath, '[' . now() . '] ' . $logMessage . PHP_EOL, FILE_APPEND);
}
function assignMainUmpireToGameIfEmpty($game_id)
{
    $game = GameModel::find($game_id);
    if ($game->ump1 == null) {
        $assignedUmpirescolumn = array();

        if (!empty($game->ump2)) {
            $assignedUmpirescolumn[] = 'ump2';
        }
        if (!empty($game->ump3)) {
            $assignedUmpirescolumn[] = 'ump3';
        }
        if (!empty($game->ump4)) {
            $assignedUmpirescolumn[] = 'ump4';
        }
        if (!empty($assignedUmpirescolumn)) {
            foreach ($assignedUmpirescolumn as $assignedUmpire) {
                $umpire_row = UmpireModel::find($game->{$assignedUmpire});
                $umpire_age = (int)get_age($umpire_row->dob);
                $league = $game->league;
                $mainumpage = (int)$league->mainumpage;
                $game_player_age = (int)$game->playersage;
                $age_diff = $umpire_age - $game_player_age;
                if ($age_diff >= $mainumpage) {
                    $game->ump1 = $game->{$assignedUmpire};
                    $game->{$assignedUmpire} = null;
                    if ($game->save()) {
                        $leagueumpire_row = $umpire_row->leagues()->where('leagueid', $league->leagueid)->first();
                        if (refund_point_to_Aumpire($leagueumpire_row, $game_id)) {
                            $updated_leagueumpire_row = LeagueUmpireModel::where('umpid', $umpire_row->umpid)->where('leagueid', $league->leagueid)->first();
                            $current_umpire_point = (int)$updated_leagueumpire_row->points;
                            $addLessPointData = addSubPoint($game_id, $umpire_row->umpid, 'ump1');
                            $addLess = $addLessPointData[0];
                            $point = $addLessPointData[1];
                            $updated_umpire_point_after_game_assigned = $current_umpire_point + ($addLess . $point);
                            //updating leagueumpire point
                            $updated_league_umpire_row_data = [
                                'points' => $updated_umpire_point_after_game_assigned
                            ];
                            if ($updated_leagueumpire_row->update($updated_league_umpire_row_data)) {
                                //storing points to a table to refund it after the game completion
                                $refund_point_data = [
                                    'leagueumpires_id' => $updated_leagueumpire_row->id,
                                    'game_id' => $game_id,
                                    'addless' => $addLess,
                                    'point' => $point,
                                ];
                                RefundPointsModel::create($refund_point_data);
                            }
                        }
                        break;
                    }
                }
            }
        }
    }
}
function reArrangeUmpiresInGames(array $game_ids)
{
    if (count($game_ids) > 0) {
        foreach ($game_ids as $gameid) {
            assignMainUmpireToGameIfEmpty($gameid);
            $j = 1;
            while ($j < 4) {
                $i = 4;
                while ($i > 1) {
                    $game = GameModel::findOrFail($gameid);
                    $current_col = "ump$i";
                    $prev_col = "ump" . ($i - 1);

                    if (empty($game->{$prev_col}) && !empty($game->{$current_col})) {
                        if ($prev_col !== 'ump1') {
                            $game->{$prev_col} = $game->{$current_col};
                            $game->{$current_col} = null;
                            $game->save();
                        }
                    }
                    $i--;
                }
                $j++;
            }
        }
    }
}

function removeUmpireFromLeague($umpId, $leagueId)
{
    try {
        $leagueUmpire = LeagueUmpireModel::where('umpid', $umpId)
            ->where('leagueid', $leagueId)->firstOrFail();
        $upcoming_games_check = GameModel::whereDate('gamedate', '>=', today())
            ->where('leagueid', $leagueId)
            ->where(function ($query) use ($umpId) {
                $query->where('ump1', $umpId)
                    ->orWhere('ump2', $umpId)
                    ->orWhere('ump3', $umpId)
                    ->orWhere('ump4', $umpId);
            })->count();
        if ($upcoming_games_check > 0) {
            return ['status' => false, 'error' => 'Umpire can not be removed from the league due to having upcoming games.'];
        }
        ApplyToLeague::where('umpid', $umpId)
            ->where('leagueid', $leagueId)->delete();
        $leagueUmpire->delete();
        return ['status' => true];
    } catch (\Throwable $th) {
        return ['status' => false, 'error' => 'Something went wrong.'];
    }
}
