<?php

namespace App\Http\Controllers;

use App\Mail\AllMail;
use App\Mail\ApplyToLeagueMail;
use Exception;
use App\Mail\OTPMail;
use App\Models\GameModel;
use App\Models\UserModel;
use App\Mail\ScheduleGame;
use App\Mail\UmpireLeaveGame;
use App\Models\LeagueModel;
use App\Models\UmpireModel;
use Illuminate\Http\Request;
use App\Models\ApplyToLeague;
use App\Models\LocationModel;
use Illuminate\Support\Carbon;
use App\Models\GameReportModel;
use App\Models\UmpirePrefModel;
use App\Models\BlockGroundModel;
use App\Models\HighlightedReportModel;
use App\Models\LeagueUmpireModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\UmpireBlockedDatesModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\LeagueApplicationAnswerModel;

class UmpireController extends Controller
{
    public function view_umpires()
    {
        $title = 'Umpires';
        $admin_data = session('admin_data');
        $page_data = UmpireModel::get();
        $data = compact('title', 'page_data', 'admin_data');
        return view('admin.umpire')->with($data);
    }
    public function delete_umpire($id)
    {
        try {
            $umpireRow = UmpireModel::find($id);
            $upcoming_games_check = GameModel::whereDate('gamedate', '>=', today())
                ->where(function ($query) use ($id) {
                    $query->where('ump1', $id)
                        ->orWhere('ump2', $id)
                        ->orWhere('ump3', $id)
                        ->orWhere('ump4', $id);
                })->count();
            if ($upcoming_games_check > 0) {
                Session::flash('error_message', 'Umpire can not be deleted due to having upcoming games.');
                return redirect()->back();
            }
            $umpireRow->user()->delete();
            $umpireRow->leagues()->delete();
            $umpireRow->applied_leagues()->delete();
            $umpireRow->delete();
            Session::flash('message', 'Success');
        } catch (\Throwable $th) {
            // dd($th);
            Session::flash('error_message', 'Something went wrong.');
        }
        return redirect()->back();
    }
    public function login_as_umpire($id)
    {
        $user = UserModel::where('uid', $id)
            ->where('usertype', 3)
            ->firstOrFail();
        session(['umpire_data' => $user]);
    }
    public function umpire_status($id, $status)
    {
        if (UmpireModel::find($id)->update(array('status' => $status))) {
            Session::flash('message', 'Success');
        } else {
            Session::flash('error_message', 'Something went wrong.');
        }
        return redirect()->back();
    }
    public function get_leaguelist($id)
    {
        $umpire_data = UmpireModel::findOrFail($id);
        $leagues = $umpire_data->leagues;
        $leagueids = [];
        if (!$leagues->isEmpty()) {
            foreach ($leagues as $league) {
                $leagueids[] = $league->leagueid;
            }
        }
        $output = '';
        $all_leagues = LeagueModel::where('status', 1)->get();
        if ($all_leagues->count() > 0) {
            foreach ($all_leagues as $single_league) {
                $output .= '<div class="col-md-6">';
                $output .= '<div class="main-leagues">';
                $output .= '<input value="' . $single_league->leagueid . '" id="league' . $single_league->leagueid . '" name="leagueids[]" type="checkbox" ' . (in_array($single_league->leagueid, $leagueids) ? "checked" : "") . '>';
                $output .= '<label for="league' . $single_league->leagueid . '">' . $single_league->leaguename . '</label>';
                $output .= '</div>';
                $output .= '</div>';
            }
        }
        echo $output;
    }
    public function assign_league(Request $request, $id)
    {
        try {
            $umpire_data = UmpireModel::findOrFail($id);
            $umpLeagues = $umpire_data->leagues;
            $assigned_leagueids = [];
            if (!$umpLeagues->isEmpty()) {
                foreach ($umpLeagues as $league) {
                    $assigned_leagueids[] = $league->leagueid;
                }
            }
            $leagueids = $request->leagueids ?? [];
            $leagueids = array_map(function ($value) {
                return (int) $value;
            }, $leagueids);
            $removedLeagueIds = array_diff($assigned_leagueids, $leagueids);
            if (!empty($leagueids)) {
                foreach ($leagueids as $leagueid) {
                    $league = LeagueModel::find($leagueid);
                    $data = [
                        'umpid' => $id,
                        'leagueid' => $leagueid,
                        'points' => $league->joiningpoint
                    ];
                    $checkData = [
                        'umpid' => $id,
                        'leagueid' => $leagueid,
                    ];
                    LeagueUmpireModel::updateOrCreate(
                        $checkData, // Search criteria
                        $data // Data to update or insert
                    );
                    //adding to umpire pref
                    $umpPref_data = [
                        'umpid' => $id,
                        'slno' => ((int)$umpire_data->pref()->orderBy('slno', 'DESC')->first()->slno + 1),
                        'leagueid' => $leagueid
                    ];
                    UmpirePrefModel::create($umpPref_data);
                    //adding to umpire pref
                }
                Session::flash('message', 'Success');
            }
            if (!empty($removedLeagueIds)) {
                foreach ($removedLeagueIds as $removedLeagueId) {
                    $res = removeUmpireFromLeague($id, $removedLeagueId);
                    if ($res['status']) {
                        Session::flash('message', 'Success');
                    } else {
                        Session::flash('error_message', $res['error']);
                    }
                }
            }
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something went wrong.!!');
        }
        return redirect()->back();
    }
    public function umpire_view()
    {
        $title = 'Umpire Home';
        $nav = 'home';
        $umpire_data = logged_in_umpire_data();
        $umpid = $umpire_data->umpid;
        $umpire_games_instance = GameModel::where(function ($query) use ($umpid) {
            $query->orWhere('ump1', $umpid)
                ->orWhere('ump2', $umpid)
                ->orWhere('ump3', $umpid)
                ->orWhere('ump4', $umpid);
        });

        $umpire_upcomming_games_instance = clone $umpire_games_instance;
        $umpire_past_games_instance = clone $umpire_games_instance;

        $umpire_games = $umpire_games_instance->get();

        $umpire_upcomming_games_grouped = $umpire_upcomming_games_instance->where('gamedate_toDisplay', '>=', now())->orderBy('gamedate_toDisplay', 'ASC')->get()->groupBy(function ($date) {
            return Carbon::parse($date->gamedate_toDisplay)->format('Y-m-d');
        });

        $umpire_past_games = $umpire_past_games_instance->where('gamedate_toDisplay', '<', now())->orderBy('gamedate_toDisplay', 'DESC')->get();


        $location_details = array();
        if ($umpire_games->count() > 0) {
            foreach ($umpire_games as $game) {
                $location = $game->location;
                $location_details[] = [
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                ];
            }
        }
        $right_bar = 1;

        $data = compact('title', 'umpire_data', 'right_bar', 'nav', 'location_details', 'umpire_past_games', 'umpire_upcomming_games_grouped');
        return view('umpire.home')->with($data);
    }
    public function showReport(){
        Session::flash('event','show-report');
        return redirect('umpire');
    }
    public function reportAbsent($gameid, $column)
    {
        $umpire_data = logged_in_umpire_data();
        reportFake($gameid, $column, $umpire_data->umpid);
        Session::flash('event','show-report');
        return redirect('umpire');
    }
    public function league_games($leagueid)
    {
        $title = 'Umpire League Games';
        $nav = 'leagues';
        $umpire_data = logged_in_umpire_data();
        $umpid = $umpire_data->umpid;
        $games = GameModel::where('leagueid', $leagueid)
            ->orderBy('gamedate', 'ASC')
            ->get();
        $location_details = array();
        if ($games->count() > 0) {
            foreach ($games as $game) {
                $location = $game->location;
                $location_details[] = [
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                ];
            }
        } else {
            Session::flash('error_message', 'No Games');
            return redirect()->back();
        }
        $right_bar = 1;
        $data = compact('title', 'umpire_data', 'right_bar', 'nav', 'location_details', 'games');
        return view('umpire.games')->with($data);
    }
    public function saveUmpire(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => ['nullable', 'regex:/^[+\d\s()-]+$/'],
            'dob' => 'required|date|before:' . now()->subYears(13)->format('Y-m-d'),
            'zipcode' => 'required',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
        ], [
            'dob.before' => 'Your age must be greater than 13.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $otp = generateOTP();
        $user_data = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 3,
            'otp' => $otp
        ];
        if ($user = UserModel::create($user_data)) {
            $umpire_data = [
                'umpid' => $user->uid,
                'name' => $request->name,
                'phone' => $request->phone,
                'zip' => $request->zipcode,
                'dob' => date('Y-m-d', strtotime($request->dob))
            ];
            try {
                UmpireModel::create($umpire_data);
                try {
                    Mail::to($request->email)->send(new OTPMail($otp, $user->uid));
                    Session::flash('message', 'Please check your email.');
                    return response()->json(['status' => 1, 'url' => url('verify-otp/' . $user->uid)]);
                } catch (Exception $e) {
                    return response()->json(['errors' => 'Mail Failed.']);
                }
            } catch (Exception $e) {
                UserModel::find($user->uid)->delete();
                return response()->json(['errors' => 'Something went wrong']);
            }
        } else {
            return response()->json(['errors' => 'Something went wrong']);
        }
    }
    public function view_leagues()
    {
        $title = 'Umpire Leagues';
        $nav = 'leagues';
        $umpire_data = logged_in_umpire_data();
        $umpire_leagues = $umpire_data->leagues;

        $excludedIds = array();
        if ($umpire_leagues->count() > 0) {
            foreach ($umpire_leagues as $umpire_league) {
                $excludedIds[] = $umpire_league->leagueid;
            }
        }
        $leagues = LeagueModel::where('status', 1)
            ->whereNotIn('leagueid', $excludedIds)
            ->where('umpire_joining_status', 1)
            ->get();

        $page_data = array();
        $right_bar = 1;
        $data = compact('title', 'page_data', 'umpire_data', 'right_bar', 'nav', 'leagues');
        return view('umpire.leagues')->with($data);
    }
    public function leave_league($id)
    {
        $umpire_data = logged_in_umpire_data();
        $res = removeUmpireFromLeague($umpire_data->umpid, $id);
        if ($res['status']) {
            Session::flash('message', 'Success');
        } else {
            Session::flash('error_message', $res['error']);
        }
        return redirect()->back();
    }
    public function apply_league($league_id)
    {
        $league = LeagueModel::find($league_id);
        $umpire_data = logged_in_umpire_data();
        $applications = $league->applications;

        $output = '';
        if ($applications->count() > 0) {
            foreach ($applications as $k => $application) {
                $output .= '
                        <div class="modalqstnbalbe">
                            <label class="qstn-label" for="">' . ($k + 1) . '. ' . $application->question . '</label>
                            <textarea required name="ans[' . $application->lqid . ']" id="" placeholder="Write your answer here..."></textarea>
                        </div>
                            ';
            }
            echo $output;
        } else {
            $apply_data = [
                'leagueid' => $league_id,
                'umpid' => $umpire_data->umpid,
                'status' => 0
            ];
            ApplyToLeague::create($apply_data);
            $msg = 'A new umpire just applied to join your league.';
            add_notification($league_id, $msg, 5, 'league');
            if ($league->email_settings->apply == 1) {
                $mail_data = compact('league', 'umpire_data');
                foreach ($league->users as $league_admin) {
                    $league_admin_email = $league_admin->email;
                    Mail::to($league_admin_email)->send(new ApplyToLeagueMail($mail_data, $league_admin_email));
                }
            }
            Session::flash('message', 'Success');
            return redirect()->back();
        }
    }
    public function save_league_apply(Request $request)
    {
        $umpire_data = logged_in_umpire_data();
        $validator = Validator::make($request->all(), [
            'league_id' => 'required',
            'ans.*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'Please answer all the questions.']);
        }
        try {
            foreach ($request->ans as $qstn => $ans) {
                $data = [
                    'leagueid' => $request->league_id,
                    'umpid' => $umpire_data->umpid,
                    'lqid' => $qstn,
                    'answer' => $ans
                ];
                LeagueApplicationAnswerModel::create($data);
            }
            $apply_data = [
                'leagueid' => $request->league_id,
                'umpid' => $umpire_data->umpid,
                'status' => 0
            ];
            // dd($apply_data);
            ApplyToLeague::create($apply_data);
            $msg = 'A new umpire just applied to join your league.';
            add_notification($request->league_id, $msg, 5, 'league');
            $league = LeagueModel::find($request->league_id);
            if ($league->email_settings->apply == 1) {
                $mail_data = compact('league', 'umpire_data');
                foreach ($league->users as $league_admin) {
                    $league_admin_email = $league_admin->email;
                    Mail::to($league_admin_email)->send(new ApplyToLeagueMail($mail_data, $league_admin_email));
                }
            }
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong..!!']);
        }
    }
    public function view_avail($type = null)
    {
        $title = 'Umpire Availability';
        $nav = 'avail';
        $umpire_data = logged_in_umpire_data();
        $page_data = $umpire_data->blocked_dates;
        $right_bar = 1;
        $data = compact('title', 'page_data', 'umpire_data', 'right_bar', 'nav');
        if ($type == null) {
            return view('umpire.avail')->with($data);
        } else {
            return view('umpire.avail4')->with($data);
        }
    }
    public function dateAvailInfo(Request $request)
    {
        $date = $request->date;
        $umpire_data = logged_in_umpire_data();
        $row = $umpire_data->blocked_dates()->where('blockdate', $date)->first();

        $output = '<form action="' . url('umpire/save_avail') . '" method="POST">';
        $output .= '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
        $output .= '<input type="hidden" name="date" value="' . $date . '" />';
        $output .= '<div class="modal-heasder">Time Availability on: <strong>' . date('M d, Y', strtotime($date)) . '</strong></div>';
        $select_fda = '';
        $select_fdna = '';
        $select_hour = '';
        $selected_hours = array();

        if ($row) {
            if ($row->blocktime !== '') {
                $selected_hours = explode(',', $row->blocktime);
            } else {
                $select_fda = 'checked';
            }
        } else {
            $select_fdna = 'checked';
        }

        $output .= '
        <div class="topinput-fels">
        <label for="fda" class="sapem" id="radioinput-lebsc1" ><input onclick="uncheck_hours();" id="fda" ' . $select_fda . ' type="radio" name="avail_type" value="fda" class="custombasc"><span class="top-radions" id="round-s1"><span class="radio-btn" ></span>Full Day Available</span></label>
        <label for="fdna" class="sapem" id="radioinput-lebsc2"  ><input onclick="uncheck_hours();" id="fdna" ' . $select_fdna . ' type="radio" name="avail_type" value="fdna" class="custombasc"><span class="top-radions" id="round-s2"><span class="radio-btn" id="round-s2"></span>Full Day not Available</span></label>
        </div>


        ';

        $output .= '<div class="input-green-check-wrap">';

        for ($hour = 0; $hour <= 11; $hour++) {
            $label = ($hour === 0) ? '12:00am' : $hour . ':00am';
            $value = ($hour === 0) ? '00:00' : sprintf('%02d:00', $hour);
            if (in_array($value, $selected_hours)) {
                $select_hour = 'checked';
            } else {
                $select_hour = '';
            }
            $output .= '<label class="lebelfor-ta-c" for="' . $value . '" ><input id="' . $value . '" onclick="uncheck_availtype();" class="hour_checkbox custombasc" ' . $select_hour . ' type="checkbox" name="times[]" value="' . $value . '"><span class="check-span-box">' . $label . '</span></label>';
        }

        for ($hour = 12; $hour <= 23; $hour++) {
            $label = ($hour === 12) ? '12:00pm' : ($hour - 12) . ':00pm';
            $value = sprintf('%02d:00', $hour);
            if (in_array($value, $selected_hours)) {
                $select_hour = 'checked';
            } else {
                $select_hour = '';
            }
            $output .= '<label class="lebelfor-ta-c" for="' . $value . '"><input id="' . $value . '" onclick="uncheck_availtype();" class="hour_checkbox custombasc" ' . $select_hour . ' type="checkbox" name="times[]" value="' . $value . '"><span class="check-span-box">' . $label . '</span></label>';
        }

        $output .= '</div>';

        $output .= '<hr class="margintpas">';

        $output .= '<div class="text-secondary font-widthsc">Click on the hours you are available.</div>';
        $output .= '<div class="text-danger font-widthsc">Selected times represents start times only.</div>';
        $output .= '<div class="mt-30px text-center">
        <button class="btn btn-danger myclass-btns redbtn"  type="submit">Save</button>
        <button class="btn btn-dark myclass-btns"  type="button" data-bs-dismiss="modal">Cancel</button>
        </div>';
        $output .= '</form>';

        echo $output;
    }
    public function makeAvailUnavail(Request $request)
    {
        try {
            $date = $request->date;
            $umpire_data = logged_in_umpire_data();
            $row = $umpire_data->blocked_dates()->where('blockdate', $date)->first();

            if ($row) {
                $row->delete();
            } else {
                $data = [
                    'umpid' => $umpire_data->umpid,
                    'blockdate' => $date,
                    'blocktime' => '',
                ];
                UmpireBlockedDatesModel::create($data);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function save_avail(Request $request)
    {
        $umpire_data = logged_in_umpire_data();
        $date = $request->date;
        $row = $umpire_data->blocked_dates()->where('blockdate', $date)->first();
        $data = [];

        if ($request->avail_type == 'fdna') {
            if ($row) {
                $row->delete();
            }
            Session::flash('message', 'Success');
            return redirect()->back();
        } elseif ($request->avail_type == 'fda') {
            $data = [
                'blockdate' => $date,
                'blocktime' => '',
            ];
        } else {
            if (!empty($request->times)) {
                $data = [
                    'blockdate' => $date,
                    'blocktime' => implode(',', $request->times),
                ];
            }
        }
        if (!empty($data)) {
            if ($row) {
                $row->update($data);
            } else {
                $data += [
                    'umpid' => $umpire_data->umpid,
                ];
                UmpireBlockedDatesModel::create($data);
            }
            Session::flash('message', 'Success');
        } else {
            Session::flash('error_message', 'Something went wrong');
        }
        return redirect()->back();
    }
    public function view_notifications()
    {
        $title = 'Umpire Notifications';
        $nav = '';
        $umpire_data = logged_in_umpire_data();
        $email_settings = $umpire_data->email_settings;

        $page_data = get_notifications($umpire_data->umpid, 3);
        $right_bar = 1;
        $data = compact('title', 'page_data', 'umpire_data', 'right_bar', 'nav', 'email_settings');
        return view('umpire.notifications')->with($data);
    }
    public function save_email_settings(Request $request)
    {
        $umpire_data = logged_in_umpire_data();
        try {
            $data = [
                'schedule_game' => $request->schedule_game ? 1 : 0,
                'payment' => $request->payment ? 1 : 0,
                'message' => $request->message ? 1 : 0,
                'application' => $request->application ? 1 : 0,
                'cancel_game' => $request->cancel_game ? 1 : 0,
            ];
            $umpire_data->email_settings()->update($data);
            Session::flash('message', 'Success');
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something Went Wrong..!!');
        }
        return redirect()->back();
    }
    public function view_profile()
    {
        $title = 'Umpire Profile';
        $nav = '';
        $umpire_data = logged_in_umpire_data();
        $blockedGround_ids = array();
        $blocked_grounds = $umpire_data->blocked_ground()->where('leagueid', 0)->get();
        if ($blocked_grounds->count() > 0) {
            foreach ($blocked_grounds as $value) {
                $blockedGround_ids[] = $value->locid;
            }
        }
        $ump_leagueids = [];
        if (!$umpire_data->leagues->isEmpty()) {
            foreach ($umpire_data->leagues as $ump_leagues) {
                $ump_leagueids[] = $ump_leagues->leagueid;
            }
        }

        $locations = LocationModel::whereIn('leagueid', $ump_leagueids)->get();
        $prefs = $umpire_data->pref()->orderBy('slno', 'ASC')->get();
        $right_bar = 1;
        $data = compact('title', 'locations', 'umpire_data', 'right_bar', 'nav', 'blockedGround_ids', 'prefs');
        return view('umpire.profile')->with($data);
    }
    public function save_profile(Request $request)
    {
        $umpire_data = logged_in_umpire_data();
        $profilepic = $umpire_data->profilepic;

        $validator = Validator::make($request->all(), [
            'file' => 'mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required',
            'dob' => 'required|date',
            'zip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed.'], 200);
        }
        if ($request->file('file')) {
            try {
                $file = $request->file('file');
                $fileName = $umpire_data->umpid . '_profileImage.png';
                $file->storeAs('images', $fileName, 'public');
                $profilepic = $fileName;
            } catch (\Throwable $th) {
                return response()->json(['status' => 0, 'message' => 'File upload failed.'], 200);
            }
        }
        try {
            $data = [
                'name' => $request->name,
                'dob' => date('Y-m-d', strtotime($request->dob)),
                'zip' => $request->zip,
                'bio' => $request->bio,
                'profilepic' => $profilepic,
            ];
            $umpire_data->update($data);
            $pic_url = asset('storage/images/' . $profilepic);
            return response()->json(['status' => 1, 'pic_url' => $pic_url], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => 0, 'message' => 'Something went wrong..!!'], 200);
        }
    }
    public function block_unblock_ground(Request $request)
    {
        $umpire_data = logged_in_umpire_data();
        $umpid = $umpire_data->umpid;

        $validator = Validator::make($request->all(), [
            'location_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0]);
        }
        $row = $umpire_data->blocked_ground()
            ->where('locid', $request->location_id)
            ->where('leagueid', 0)
            ->first();
        if ($row) {
            $row->delete();
        } else {
            $data = [
                'leagueid' => 0,
                'umpid' => $umpid,
                'locid' => $request->location_id,
            ];
            BlockGroundModel::create($data);
        }
        return response()->json(['status' => 1]);
    }
    public function view_change_password()
    {
        $title = 'Umpire Change Password';
        $nav = '';
        $umpire_data = logged_in_umpire_data();
        $right_bar = 1;
        $data = compact('title', 'umpire_data', 'right_bar', 'nav');
        return view('umpire.change_password')->with($data);
    }
    public function update_umpire_pref(Request $request)
    {
        $orders = $request->order;
        foreach ($orders as $order) {
            $data = [
                'slno' => $order['position'],
            ];
            UmpirePrefModel::find($order['id'])
                ->update($data);
        }
    }
    public function manual_assign($gameid, $pos)
    {
        $umpire = logged_in_umpire_data();
        $umpid = $umpire->umpid;
        $game = GameModel::findOrFail($gameid);
        $league = $game->league;
        $date1 = Carbon::parse($game->gamedate);
        $now = Carbon::now();
        $diffInDays = $now->diffInDays($date1);
        $res = [];

        if ($diffInDays <= $league->assignbefore) {
            $league_umpire = $umpire->leagues()->where('leagueid', $game->leagueid)->first();
            if ($league_umpire->status !== 1) {
                $condition_met = false;
                $umpire_age = (int)get_age($umpire->dob);
                $mainumpage = (int)$league->mainumpage;
                $otherumpage = (int)$league->otherumpage;
                $game_date = explode(' ', $game->gamedate)[0];
                $game_time = substr(explode(' ', $game->gamedate)[1], 0, 5);
                $game_teams = array($game->hometeamid, $game->awayteamid);

                // Checking umpire blocked dates and time
                //due to new changes consider *blocked_dates* as *available_dates*
                $blocked_dates = $umpire->blocked_dates;
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
                                    Session::flash('error_message', 'You are not available on the game time.');
                                }
                            } else {
                                $condition_met = false; // Set the flag to true if date is blocked
                                break; // Exit this loop
                            }
                        } else {
                            $condition_met = true; // Set the flag to true if time is blocked
                            Session::flash('error_message', 'You are not available on the game time.');
                        }
                    }
                } else {
                    $condition_met = true; // Set the flag to true if time is blocked
                    Session::flash('error_message', 'You are not available on the game time.');
                }

                // Checking umpire's blocked grounds
                $blocked_grounds = $umpire->blocked_ground;
                foreach ($blocked_grounds as $blocked_ground) {
                    if ($blocked_ground->locid == $game->locid) {
                        $condition_met = true; // Set the flag to true if ground is blocked
                        Session::flash('error_message', 'You can\'t join for this location.');
                        break; // Exit this loop
                    }
                }
                // Checking umpire's blocked teams
                $blocked_teams = $umpire->blocked_team;
                foreach ($blocked_teams as $blocked_team) {
                    if (in_array($blocked_team->teamid, $game_teams)) {
                        $condition_met = true; // Set the flag to true if team is blocked
                        Session::flash('error_message', 'You are blocked from games which includes "' . htmlspecialchars($blocked_team->team->teamname) . '".');
                        break; // Exit this loop
                    }
                }
                //checking umpire age
                $game_player_age = (int)$game->playersage;
                $age_diff = $umpire_age - $game_player_age;
                if ($pos == 'ump1') {
                    if ($age_diff < $mainumpage) {
                        $condition_met = true; // Set the flag to true if age diff is lower
                        Session::flash('error_message', 'You don\'t meet the game\'s age requirement.');
                    }
                } else {
                    if ($age_diff < $otherumpage) {
                        $condition_met = true; // Set the flag to true if age diff is lower
                        Session::flash('error_message', 'You don\'t meet the game\'s age requirement.');
                    }
                }
                //check if have other games on the same datetime
                $gapMorethnTwo = 0;
                $gamedatetime = $game->gamedate;
                $samedategames = GameModel::whereDate('gamedate', explode(' ', $gamedatetime)[0])
                    ->where(function ($query) use ($umpid) {
                        $query->orWhere('ump1', $umpid)
                            ->orWhere('ump2', $umpid)
                            ->orWhere('ump3', $umpid)
                            ->orWhere('ump4', $umpid);
                    })->get();

                if (!$samedategames->isEmpty()) {
                    $gapMorethnTwo = 1;
                    foreach ($samedategames as $samedategame) {
                        $samedategamedatetime = $samedategame->gamedate;
                        $gameDateTimeObj = Carbon::parse($gamedatetime);
                        $sameDateGameDateTimeObj = Carbon::parse($samedategamedatetime);

                        if ($gameDateTimeObj->diffInHours($sameDateGameDateTimeObj) < 2) {
                            $gapMorethnTwo = 2;
                            break;
                        }
                    }

                    $condition_met = true; // Set the flag to true if found another game on the same datetime
                }

                if (!$condition_met) {
                    if ($this->assign_ump_toAgamePosition($gameid, $pos, 1)) {
                        Session::flash('message', 'Success');
                        $res = ['status' => 1];
                    } else {
                        Session::flash('error_message', 'Something went wrong..!!');
                        $res = ['status' => 0];
                    }
                } else {
                    if ($gapMorethnTwo == 1) {
                        $res = ['status' => 2, 'gameid' => $gameid, 'pos' => $pos]; //for same gamedate
                    } elseif ($gapMorethnTwo == 2) {
                        $res = ['status' => 0];
                        Session::flash('error_message', 'Difference between your assigned games are less than 2 hours.');
                    }
                }
            } else {
                Session::flash('error_message', 'You are blocked from this league.');
                $res = ['status' => 0];
            }
        } else {
            Session::flash('error_message', 'Umpire assigning on this game hasn\'t been started yet.');
            $res = ['status' => 0];
        }
        return response()->json($res);
    }
    public function assign_ump_toAgamePosition($gameid, $pos, $returnType = 0)
    {
        try {
            $umpire = logged_in_umpire_data();
            $umpid = $umpire->umpid;
            $game = GameModel::findOrFail($gameid);
            $league = $game->league;
            $game->{$pos} = $umpid;
            $game->save();
            $msg = $umpire->name . ' assigned to a game on ' . date('D m/d/y', strtotime($game->gamedate));
            add_notification($game->leagueid, $msg, 4, 'league');
            if ($league->email_settings->join_game == 1) {
                foreach ($league->users as $league_admin) {
                    $league_admin_email = $league_admin->email;
                    Mail::to($league_admin_email)->send(new ScheduleGame($league, $umpire, $game, 'league', $league_admin_email));
                }
            }
            if ($returnType == 1) {
                return true;
            } else {
                Session::flash('message', 'Success');
                return redirect()->back();
            }
        } catch (Exception $e) {
        }
    }
    public function view_games()
    {
        $title = 'Open Games';
        $nav = 'games';
        $umpire_data = logged_in_umpire_data();
        $leagues = $umpire_data->leagues;
        $leagueids = [];
        if (!$leagues->isEmpty()) {
            foreach ($leagues as $league) {
                $leagueids[] = $league->leagueid;
            }
        }
        $upcomming_games = GameModel::where('gamedate', '>=', now())
            ->whereIn('leagueid', $leagueids)
            ->orderBy('gamedate', 'ASC')
            ->get();
        $right_bar = 0;
        $data = compact('title', 'upcomming_games', 'umpire_data', 'right_bar', 'nav');
        return view('umpire.open_games')->with($data);
    }
    public function cancel_game($gameid)
    {
        try {
            $umpire = logged_in_umpire_data();
            $umpid = $umpire->umpid;
            $game = GameModel::findOrFail($gameid);
            $league = $game->league;
            $leagueumpire = $league->umpires()->where('umpid', $umpid)->firstOrfail();

            if ($game->ump1 === $umpid) {
                $game->ump1 = null;
            } elseif ($game->ump2 === $umpid) {
                $game->ump2 = null;
            } elseif ($game->ump3 === $umpid) {
                $game->ump3 = null;
            } elseif ($game->ump4 === $umpid) {
                $game->ump4 = null;
            }

            if ($game->save()) {
                refund_point_to_Aumpire($leagueumpire, $gameid);
                $msg = $umpire->name . ' canceled a game on ' . date('D m/d/y', strtotime($game->gamedate));
                add_notification($game->leagueid, $msg, 6, 'league');
                if ($league->email_settings->leave_game == 1) {
                    $mail_data = compact('league', 'umpire', 'game');
                    foreach ($league->users as $league_admin) {
                        $league_admin_email = $league_admin->email;
                        Mail::to($league_admin_email)->send(new UmpireLeaveGame($mail_data, $league_admin_email));
                    }
                }
                reArrangeUmpiresInGames([$gameid]);
                Session::flash('message', 'Success');
            }
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something went wrong..!!');
        }
        return redirect()->back();
    }
    public function submit_report($gameid)
    {
        $game = GameModel::findOrFail($gameid);
        $league = $game->league;
        $reports = $league->reports;
        $output = '';
        if (!$reports->isEmpty()) {
            foreach ($reports as $k => $report) {
                $output .= '
                <div class="modalqstnbalbe">
                    <label class="qstn-label" for="">' . ($k + 1) . '. ' . $report->question . '</label>
                    <textarea required name="ans[' . $report->rqid . ']" id="" placeholder="Answer here"></textarea>
                </div>
                    ';
            }
        } else {
            dd();
        }
        echo $output;
    }
    public function save_report(Request $request)
    {
        try {
            $game_id = $request->input('game_id');
            $game = GameModel::find($game_id);
            $report_column = $request->input('report_column');
            $toggle_email = $request->toggle_email_noti;
            $umpid = logged_in_umpire_data()->umpid;
            $answers = $request->input('ans');
            $report_ids = [];
            if (!empty($answers)) {
                foreach ($answers as $k => $val) {
                    $data = [
                        'gameid' => $game_id,
                        'umpid' => $umpid,
                        'rqid' => $k,
                        'answer' => $val
                    ];
                    $inserted_row = GameReportModel::create($data);
                    $report_ids[] = $inserted_row->grid;
                }
            }
            $report_col_data = implode(',', $report_ids);
            $game->{$report_column} = $report_col_data;
            $game->save();
            $res = ['status' => 1, 'gameid' => $game_id];
            $msg = 'Umpire submitted report for the game on ' . date('D m/d/y', strtotime($game->gamedate));
            if ($toggle_email) {
                $toggle_email_data = [
                    'gameid' => $game_id,
                    'report_col' => $report_column,
                ];
                HighlightedReportModel::create($toggle_email_data);
                try {
                    $leagueAdminEmails = $game->league->users()->pluck('email');
                    $subject = 'Umpire Report Submit';
                    Mail::to($leagueAdminEmails)->send(new AllMail($subject, $msg));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            add_notification($game->leagueid, $msg, 6, 'league');
        } catch (\Throwable $th) {
            $res = ['status' => 0];
        }
        return response()->json($res);
    }
    public function view_earnings()
    {
        $title = 'My Earnings';
        $nav = 'earning';
        $umpire_data = logged_in_umpire_data();
        $fromdate = request('fromdate', null);
        $todate = request('todate', null);
        if ($fromdate == null && $todate == null) {
            $payouts = $umpire_data->payouts()
                ->orderBy('id', 'DESC')
                ->get();;
        } else {
            $fromdate = date('Y-m-d', strtotime(request('fromdate', null)));
            $todate = date('Y-m-d', strtotime(request('todate', null)));
            $payouts = $umpire_data->payouts()
                ->whereBetween('paydate', [$fromdate, $todate])
                ->orderBy('paydate', 'ASC')
                ->get();
        }
        $right_bar = 1;
        $last_update = date('M jS');
        $last_payout = $umpire_data->payouts()->orderBy('created_at', 'DESC')->first();
        if ($last_payout !== null) {
            $last_update = Carbon::parse($last_payout->created_at)->format('M jS');
        }
        $data = compact('title', 'umpire_data', 'right_bar', 'nav', 'payouts', 'fromdate', 'todate', 'last_update');
        return view('umpire.earnings')->with($data);
    }
    public function barchart()
    {
        $year = request('year', date('Y'));
        $umpire = logged_in_umpire_data();
        $payouts = $umpire->payouts()
            ->whereYear('paydate', $year)
            ->orderBy('paydate', 'ASC')
            ->get();
        $monthlyTotals = [];

        if (!$payouts->isEmpty()) {
            foreach ($payouts as $payout) {
                $paydate = $payout->paydate;
                $payamt = $payout->payamt;
                $month = date('M', strtotime($paydate));
                if (array_key_exists($month, $monthlyTotals)) {
                    $monthlyTotals[$month] += $payamt;
                } else {
                    $monthlyTotals[$month] = $payamt;
                }
            }
        }
        return response()->json($monthlyTotals);
    }
    public function piechart()
    {
        $umpire = logged_in_umpire_data();
        $payouts = $umpire->payouts()->get();

        $data = [];

        if (!$payouts->isEmpty()) {
            $groupedPayouts = $payouts->groupBy('leagueid');

            foreach ($groupedPayouts as $leagueid => $payouts) {
                $league = LeagueModel::find($leagueid);

                if ($league) {
                    $leagename = $league->leaguename;
                    $cc = $league->cc;
                    $totalPayamt = $payouts->sum('payamt');

                    $data[] = [
                        'label' => $leagename,
                        'color' => $cc,
                        'value' => $totalPayamt,
                    ];
                }
            }
        }
        return response()->json($data);
    }
    public function edit_umpire($id)
    {
        $title = 'Edit Umpire';
        $admin_data = logged_in_admin_data();
        $page_data = UmpireModel::find($id);
        $data = compact('title', 'admin_data', 'page_data');
        return view('admin.edit_umpire')->with($data);
    }
    public function save_umpire(Request $request, $umpid)
    {
        $request->validate([
            'name' => 'required',
            'phone' => ['nullable', 'regex:/^[+\d\s()-]+$/'],
            'dob' => 'required|date|before:' . now()->subYears(13)->format('Y-m-d'),
            'zip' => 'required',
        ], [
            'dob.before' => 'Umpires age must be greater than 13.',
        ]);
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'zip' => $request->zip,
            'bio' => $request->bio,
        ];
        $row = UmpireModel::find($umpid);
        if ($row) {
            $row->update($data);
            Session::flash('message', 'Success');
        }
        return redirect()->back();
    }
}
