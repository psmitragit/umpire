<?php

namespace App\Http\Controllers;

use Exception;
use ZipArchive;
use App\Mail\CancelGame;
use App\Models\GameModel;
use App\Models\TeamModel;
use App\Models\UserModel;
use App\Exports\AllSheets;
use App\Exports\GameSheet;
use App\Mail\ScheduleGame;
use App\Imports\GameImport;
use App\Models\LeagueModel;
use App\Models\PayoutModel;
use App\Models\PresetModel;
use App\Models\ReportModel;
use App\Models\UmpireModel;
use Illuminate\Http\Request;
use App\Models\LocationModel;
use App\Mail\SentLeagueInvite;
use App\Models\BlockTeamModel;
use App\Models\PayLeagueModel;
use Illuminate\Support\Carbon;
use App\Mail\ApproveUmpireMail;
use App\Mail\DeclineUmpireMail;
use App\Mail\LeagueMessageMail;
use App\Models\GameReportModel;
use App\Models\TimeLeagueModel;
use App\Models\UmpirePrefModel;
use Illuminate\Validation\Rule;
use App\Models\BlockGroundModel;
use App\Models\BlockUmpireModel;
use App\Mail\InterviewUmpireMail;
use App\Models\GroundLeagueModel;
use App\Models\LeagueUmpireModel;
use App\Models\NotificationModel;
use App\Mail\SentLeagueAdminInvite;
use App\Models\ScheduleLeagueModel;
use App\Models\DayofWeekLeagueModel;
use App\Models\TotalGameLeagueModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use App\Mail\InterviewLeagueAdminMail;
use App\Models\LeagueApplicationModel;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\LeagueEmailSettingsModel;
use App\Models\Age_of_PlayersLeagueModel;
use App\Models\BlockDivisionModel;
use App\Models\TeamDivisionModel;
use App\Models\UmpireDurationLeagueModel;
use App\Models\UmpirePositionLeagueModel;
use Illuminate\Support\Facades\Validator;

class LeagueController extends Controller
{
    public function view_league()
    {
        $title = 'Leagues';
        $admin_data = session('admin_data');
        $page_data = LeagueModel::get();
        $data = compact('title', 'page_data', 'admin_data');
        return view('admin.league')->with($data);
    }
    public function login_as_league_admin($id)
    {
        $user = UserModel::where('uid', $id)
            ->where('usertype', 2)
            ->firstOrFail();
        session(['league_data' => $user]);
    }
    public function sent_invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Mail Already Exist.');
            return redirect()->back();
        }
        try {
            $encryptedEmail = Crypt::encryptString($request->email);
            Mail::to($request->email)->send(new SentLeagueInvite($encryptedEmail));
            Session::flash('message', 'Invitation sent.');
        } catch (Exception $e) {
            Session::flash('error_message', 'Mail Failed.');
        }
        return redirect()->back();
    }
    public function view_league_admin()
    {
        $title = 'League Admins';
        $admin_data = session('admin_data');
        $leagues = LeagueModel::get();
        $page_data = UserModel::where('usertype', 2)->get();
        $data = compact('title', 'page_data', 'admin_data', 'leagues');
        return view('admin.league_admin')->with($data);
    }
    public function sent_invite_league_admin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'leagueid' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Mail Already Exist.');
            return redirect()->back();
        }
        try {
            $encryptedEmail = Crypt::encryptString($request->email);
            $encryptedLeagueid = Crypt::encryptString($request->leagueid);
            $league = LeagueModel::find($request->leagueid);
            $encData = compact('encryptedEmail', 'encryptedLeagueid', 'league');
            Mail::to($request->email)->send(new SentLeagueAdminInvite($encData));
            Session::flash('message', 'Invitation sent.');
        } catch (Exception $e) {
            Session::flash('error_message', 'Mail Failed.');
        }
        return redirect()->back();
    }
    public function addEditLeague($id = null)
    {
        $title = 'Add League';
        $admin_data = session('admin_data');
        $page_data = '';
        if ($id !== null) {
            $title = 'Update League';
            $page_data = LeagueModel::find($id);
        }
        $data = compact('title', 'admin_data', 'page_data');
        return view('admin.add_league')->with($data);
    }
    public function saveLeague(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'leaguename' => 'required',
            'phone' => ['nullable', 'regex:/^[+\d\s()-]+$/'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user_data = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'isLeagueOwner' => 1,
            'usertype' => 2
        ];
        if ($user = UserModel::create($user_data)) {
            $cc = getUniqueColor();
            $league_data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'leaguename' => $request->leaguename,
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
                Session::flash('message', 'Success');
                return response()->json(['status' => 1]);
            } catch (Exception $e) {
                UserModel::find($user->uid)->delete();
                return response()->json(['errors' => 'Something went wrong']);
            }
        } else {
            return response()->json(['errors' => 'Something went wrong']);
        }
    }
    public function saveLeagueAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'leagueid' => 'numeric',
            'password' => [
                'required',
                'confirmed',
                'min:8'
            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user_data = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 2,
            'leagueid' => $request->leagueid,
        ];
        if (UserModel::create($user_data)) {
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } else {
            return response()->json(['errors' => 'Something went wrong']);
        }
    }
    public function saveLeagueAsadmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'leaguename' => 'required',
            'phone' => ['nullable', 'regex:/^[+\d\s()-]+$/'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $cc = getUniqueColor();
        $league_data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'leaguename' => $request->leaguename,
            'status' => 1,
            'cc' => $cc,
        ];
        try {
            $league = LeagueModel::create($league_data);
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            return response()->json(['errors' => 'Something went wrong']);
        }
    }
    public function updateLeague(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'leaguename' => 'required',
            'phone' => ['nullable', 'regex:/^[+\d\s()-]+$/'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $league_data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'leaguename' => $request->leaguename,
        ];
        if (LeagueModel::find($id)->update($league_data)) {
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } else {
            return response()->json(['message' => 'Something went wrong']);
        }
    }
    public function league_status($id, $status)
    {
        if (LeagueModel::find($id)->update(array('status' => $status))) {
            Session::flash('message', 'Success');
        } else {
            Session::flash('error_message', 'Something went wrong.');
        }
        return redirect()->back();
    }
    public function league_admin_status($id, $status)
    {
        if (UserModel::find($id)->update(array('status' => $status))) {
            Session::flash('message', 'Success');
        } else {
            Session::flash('error_message', 'Something went wrong.');
        }
        return redirect()->back();
    }
    public function league_view()
    {
        $title = 'League Home';
        $nav = 'home';
        $league_data = logged_in_league_data();
        $page_data = array();
        $right_bar = 0;
        $league_games_instance = GameModel::where('leagueid', $league_data->leagueid);

        $league_upcomming_games_instance = clone $league_games_instance;
        $league_past_games_instance = clone $league_games_instance;
        $league_upcomming_games_grouped = $league_upcomming_games_instance->where('gamedate_toDisplay', '>=', now())->orderBy('gamedate_toDisplay', 'ASC')->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->gamedate_toDisplay)->format('Y-m-d');
            });
        $league_past_games = $league_past_games_instance->where('gamedate_toDisplay', '<', now())->orderBy('gamedate_toDisplay', 'DESC')->get();
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'league_upcomming_games_grouped', 'league_past_games');
        return view('league.home')->with($data);
    }
    public function reportAbsent($gameid, $column, $umpid)
    {
        reportFake($gameid, $column, $umpid);
        Session::flash('event','show-report');
        return redirect('league');
    }
    public function view_report($gameid, $col)
    {
        $game = GameModel::findOrfail($gameid);
        $report_ids = explode(',', $game->{$col});
        $output = '';
        foreach ($report_ids as $k => $report_id) {
            $report = GameReportModel::find($report_id);
            $output .= '
                <div class="modalqstnbalbe">
                    <label class="qstn-label" for="">' . ($k + 1) . '. ' . $report->question->question . '</label>
                    <textarea readonly>' . $report->answer . '</textarea>
                </div>
                    ';
        }
        echo $output;
    }
    public function view_settings()
    {
        $title = 'League Settings';
        $nav = 'settings';
        $active_sub_nav_bar = 'general';
        $league_data = logged_in_league_data();
        $page_data = array();
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar');
        return view('league.settings')->with($data);
    }
    public function update_report_order(Request $request)
    {
        $orders = $request->order;
        foreach ($orders as $order) {
            $data = [
                'order' => $order['position'],
            ];
            ReportModel::find($order['id'])
                ->update($data);
        }
    }
    public function view_report_settings()
    {
        $title = 'League Report Settings';
        $nav = 'settings';
        $active_sub_nav_bar = 'report';
        $league_data = logged_in_league_data();
        $page_data = $league_data->reports;
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar');
        return view('league.report_settings')->with($data);
    }
    public function view_notification_settings()
    {
        $title = 'League Notification Settings';
        $nav = 'settings';
        $active_sub_nav_bar = 'notification';
        $league_data = logged_in_league_data();
        $page_data = $league_data->notifications()->where('type', 1)->get();
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar');
        return view('league.notification_settings')->with($data);
    }
    public function view_league_application_settings()
    {
        $title = 'League League Application Settings';
        $nav = 'settings';
        $active_sub_nav_bar = 'application';
        $league_data = logged_in_league_data();
        $page_data = $league_data->applications;
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar');
        return view('league.application_settings')->with($data);
    }
    public function view_team()
    {
        $title = 'League Team';
        $nav = 'settings';
        $active_sub_nav_bar = 'teams';
        $league_data = logged_in_league_data();
        $page_data = $league_data->teams;
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar');
        return view('league.teams')->with($data);
    }
    public function view_division()
    {
        $title = 'League Division';
        $nav = 'settings';
        $active_sub_nav_bar = 'divisions';
        $league_data = logged_in_league_data();
        $page_data = $league_data->divisions;
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar');
        return view('league.divisions')->with($data);
    }
    public function view_location()
    {
        $title = 'League Location';
        $nav = 'settings';
        $active_sub_nav_bar = 'location';
        $league_data = logged_in_league_data();
        $page_data = $league_data->location;
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar');
        return view('league.location')->with($data);
    }
    public function save_general_settings(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'joiningpoint' => 'required|numeric',
            'assignbefore' => 'required|numeric',
            'leavebefore' => 'required|numeric',
            'defaultpay' => 'required|numeric',
            'mainumpage' => 'required|numeric',
            'otherumpage' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $data = [
                'joiningpoint' => $request->joiningpoint,
                'assignbefore' => $request->assignbefore,
                'leavebefore' => $request->leavebefore,
                'defaultpay' => $request->defaultpay,
                'mainumpage' => $request->mainumpage,
                'otherumpage' => $request->otherumpage,
                'umpire_joining_status' => $request->umpire_joining_status ?? 0,
            ];
            LeagueModel::find($league_data->leagueid)->update($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(['errors' => 'Something went wrong']);
        }
    }
    public function save_report_settings(Request $request)
    {
        $league_data = logged_in_league_data();
        try {
            $data = [
                'report' => $request->report,
            ];
            LeagueModel::find($league_data->leagueid)->update($data);
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_report_question(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'question' => $request->question,
            ];
            ReportModel::create($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function update_report_question(Request $request, $id)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'question' => $request->question,
            ];
            ReportModel::find($id)->update($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function delete_report($id)
    {
        ReportModel::find($id)->delete();
        Session::flash('message', 'Success');
        return redirect()->back();
    }
    public function send_notification(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'leaguemsg' => $request->message,
                'type' => 1,
                'iconid' => 1,
            ];
            NotificationModel::create($data);
            //notification mail
            $umpires = $league_data->umpires;
            if ($umpires->count() > 0) {
                foreach ($umpires as $umpire) {
                    $ump_row = $umpire->umpire;
                    if ($ump_row->email_settings->message == 1) {
                        $umpire_email = $ump_row->user->email;
                        Mail::to($umpire_email)->send(new LeagueMessageMail($data['leaguemsg'], $ump_row, $league_data, $umpire_email));
                    }
                }
            }
            //notification mail end
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function save_league_application_question(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'question' => $request->question,
            ];
            LeagueApplicationModel::create($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function update_league_application_question(Request $request, $id)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'question' => $request->question,
            ];
            LeagueApplicationModel::find($id)->update($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function update_league_application_order(Request $request)
    {
        $orders = $request->order;
        foreach ($orders as $order) {
            $data = [
                'order' => $order['position'],
            ];
            LeagueApplicationModel::find($order['id'])
                ->update($data);
        }
    }
    public function delete_application($id)
    {
        LeagueApplicationModel::find($id)->delete();
        Session::flash('message', 'Success');
        return redirect()->back();
    }
    public function save_team(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'teamname' => $request->question,
                'divid' => $request->divid,
            ];
            TeamModel::create($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function save_division(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'name' => $request->question,
            ];
            TeamDivisionModel::create($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function update_team(Request $request, $id)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'teamname' => $request->question,
                'divid' => $request->divid,
            ];
            TeamModel::find($id)->update($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function update_division(Request $request, $id)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'name' => $request->question,
            ];
            TeamDivisionModel::find($id)->update($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function delete_team($id)
    {
        $upcoming_games_check = GameModel::whereDate('gamedate', '>=', today())
            ->where(function ($query) use ($id) {
                $query->where('hometeamid', $id)
                    ->orWhere('awayteamid', $id);
            })->count();
        if ($upcoming_games_check > 0) {
            Session::flash('error_message', 'Team can not be deleted due to having upcoming games.');
            return redirect()->back();
        }
        $row = TeamModel::find($id);
        $row->blocked_umpire_teams()->delete();
        $row->delete();
        Session::flash('message', 'Success');
        return redirect()->back();
    }
    public function delete_division($id)
    {
        $row = TeamDivisionModel::find($id);
        $row->blockedDivisions()->delete();
        TeamModel::where('divid', $id)->update(['divid' => null]);
        $row->delete();
        Session::flash('message', 'Success');
        return redirect()->back();
    }
    public function delete_location($id)
    {
        $upcoming_games_check = GameModel::whereDate('gamedate', '>=', today())
            ->where('locid', $id)->count();
        if ($upcoming_games_check > 0) {
            Session::flash('error_message', 'Location can not be deleted due to having upcoming games.');
            return redirect()->back();
        }
        $row = LocationModel::find($id);
        $row->blocked_umpire_grounds()->delete();
        $row->delete();
        Session::flash('message', 'Success');
        return redirect()->back();
    }
    public function save_location(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'ground' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'ground' => $request->ground,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ];
            LocationModel::create($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function update_location(Request $request, $id)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'ground' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        if ($validator->fails()) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
        try {
            $data = [
                'leagueid' => $league_data->leagueid,
                'ground' => $request->ground,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ];
            LocationModel::find($id)->update($data);
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return response()->json(array('status' => 0));
        }
    }
    public function view_point($type)
    {
        $title = 'League Point Settings';
        $nav = 'settings';
        $active_sub_nav_bar = 'points';
        $league_data = logged_in_league_data();
        $right_bar = 1;
        $all_presets = PresetModel::get();
        if ($type == 'schedule-on-any-game') {
            $title = 'Schedule on Any Game';
            $page_data = $league_data->schedule[0] ?? array();
            $template = 'base_point';
            $point_menu = 1;
        } elseif ($type == 'age-of-players') {
            $title = 'Age of Players';
            $page_data = $league_data->age_of_players ?? array();
            $template = 'age_of_players';
            $point_menu = 2;
        } elseif ($type == 'location') {
            $title = 'Location';
            $page_data = $league_data->locations ?? array();
            $template = 'preset_location';
            $point_menu = 3;
        } elseif ($type == 'pay') {
            $title = 'Pay';
            $page_data = $league_data->pay ?? array();
            $template = 'pay_preset';
            $point_menu = 4;
        } elseif ($type == 'time') {
            $title = 'Time';
            $page_data = $league_data->time ?? array();
            $template = 'time_preset';
            $point_menu = 5;
        } elseif ($type == 'umpire-duration') {
            $title = 'Umpire Duration';
            $page_data = $league_data->umpire_duration ?? array();
            $template = 'umpire_duration';
            $point_menu = 8;
        } elseif ($type == 'total-game') {
            $title = 'Total Game';
            $page_data = $league_data->total_game ?? array();
            $template = 'total_game';
            $point_menu = 9;
        } elseif ($type == 'umpire-position') {
            $title = 'Umpire Position';
            $page_data = $league_data->umpire_position ?? array();
            $template = 'umpire_position';
            $point_menu = 7;
        } elseif ($type == 'day-of-week') {
            $title = 'Day Of Week';
            $page_data = $league_data->day_of_week ?? array();
            $template = 'day_of_week';
            $point_menu = 6;
        } else {
            Session::flash('error_message', 'Not Authorized.');
            return redirect('league/settings');
        }
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'active_sub_nav_bar', 'point_menu', 'all_presets');
        return view('league.' . $template)->with($data);
    }
    public function save_base_point(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'addlesss' => 'required',
            'point' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $data = [
                'leagueid' => $league_id,
                'addless' => $request->addlesss,
                'point' => $request->point
            ];
            LeagueModel::find($league_id)->schedule()->delete();
            ScheduleLeagueModel::create($data);
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_age_of_players(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->age_of_players()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                Age_of_PlayersLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_location_preset(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'locid.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->locations()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'locid' => $request->locid[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                GroundLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_pay(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->pay()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                PayLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_time(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->time()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                TimeLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_umpire_duration(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->umpire_duration()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                UmpireDurationLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
    public function save_total_game(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->total_game()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                TotalGameLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
    public function save_umpire_position(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'position.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->umpire_position()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'position' => $request->position[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                UmpirePositionLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
    public function save_day_of_week(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $validator = Validator::make($request->all(), [
            'dayname.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            LeagueModel::find($league_id)->day_of_week()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'leagueid' => $league_id,
                    'dayname' => $request->dayname[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                DayofWeekLeagueModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function set_preset(Request $request)
    {
        $league_data = logged_in_league_data();
        $league_id = $league_data->leagueid;
        $preset_id = $request->preset_id;
        $preset_row = PresetModel::find($preset_id);
        try {
            //set schedule point
            $league_data->schedule()->delete();
            if ($preset_schedule = $preset_row->schedule[0]) {
                $schedule_data = [
                    'leagueid' => $league_id,
                    'addless' => $preset_schedule->addless,
                    'point' => $preset_schedule->point
                ];
                ScheduleLeagueModel::create($schedule_data);
            }
            //set age of players
            $league_data->age_of_players()->delete();
            if ($preset_age_of_players = $preset_row->age_of_players) {
                foreach ($preset_age_of_players as $preset_age_of_player) {
                    $age_of_players_data = [
                        'leagueid' => $league_id,
                        'from' => $preset_age_of_player->from,
                        'to' => $preset_age_of_player->to,
                        'addless' => $preset_age_of_player->addless,
                        'point' => $preset_age_of_player->point
                    ];
                    Age_of_PlayersLeagueModel::create($age_of_players_data);
                }
            }
            //set location
            $league_data->locations()->delete();
            if ($preset_locations = $preset_row->locations) {
                foreach ($preset_locations as $location) {
                    $locations_data = [
                        'leagueid' => $league_id,
                        'locid' => $location->locid,
                        'addless' => $location->addless,
                        'point' => $location->point
                    ];
                    GroundLeagueModel::create($locations_data);
                }
            }
            //set pay
            $league_data->pay()->delete();
            if ($preset_pay = $preset_row->pay) {
                foreach ($preset_pay as $pay) {
                    $pay_data = [
                        'leagueid' => $league_id,
                        'from' => $pay->from,
                        'to' => $pay->to,
                        'addless' => $pay->addless,
                        'point' => $pay->point
                    ];
                    PayLeagueModel::create($pay_data);
                }
            }
            //set time
            $league_data->time()->delete();
            if ($preset_time = $preset_row->time) {
                foreach ($preset_time as $time) {
                    $time_data = [
                        'leagueid' => $league_id,
                        'from' => $time->from,
                        'to' => $time->to,
                        'addless' => $time->addless,
                        'point' => $time->point
                    ];
                    TimeLeagueModel::create($time_data);
                }
            }
            //set day of week
            $league_data->day_of_week()->delete();
            if ($preset_day_of_week = $preset_row->day_of_week) {
                foreach ($preset_day_of_week as $day_of_week) {
                    $day_of_week_data = [
                        'leagueid' => $league_id,
                        'dayname' => $day_of_week->dayname,
                        'addless' => $day_of_week->addless,
                        'point' => $day_of_week->point
                    ];
                    DayofWeekLeagueModel::create($day_of_week_data);
                }
            }
            //set umpire position
            $league_data->umpire_position()->delete();
            if ($preset_umpire_position = $preset_row->umpire_position) {
                foreach ($preset_umpire_position as $umpire_position) {
                    $umpire_position_data = [
                        'leagueid' => $league_id,
                        'position' => $umpire_position->position,
                        'addless' => $umpire_position->addless,
                        'point' => $umpire_position->point
                    ];
                    UmpirePositionLeagueModel::create($umpire_position_data);
                }
            }
            //set umpire duration
            $league_data->umpire_duration()->delete();
            if ($preset_umpire_duration = $preset_row->umpire_duration) {
                foreach ($preset_umpire_duration as $umpire_duration) {
                    $umpire_duration_data = [
                        'leagueid' => $league_id,
                        'from' => $umpire_duration->from,
                        'to' => $umpire_duration->to,
                        'addless' => $umpire_duration->addless,
                        'point' => $umpire_duration->point
                    ];
                    UmpireDurationLeagueModel::create($umpire_duration_data);
                }
            }
            //set total game
            $league_data->total_game()->delete();
            if ($preset_total_game = $preset_row->total_game) {
                foreach ($preset_total_game as $total_game) {
                    $total_game_data = [
                        'leagueid' => $league_id,
                        'from' => $total_game->from,
                        'to' => $total_game->to,
                        'addless' => $total_game->addless,
                        'point' => $total_game->point
                    ];
                    TotalGameLeagueModel::create($total_game_data);
                }
            }
            Session::flash('message', 'Success');
        } catch (Exception $e) {
            // dd($e);
            Session::flash('error_message', 'Something went wrong..!!');
        }
    }
    public function view_games()
    {
        $league_data = logged_in_league_data();
        if (!$league_data->schedule->isEmpty()) {
            $title = 'League Games';
            $nav = 'games';
            $page_data = $league_data->games()
                ->where('gamedate', '>=', now())
                ->orderBy('gamedate', 'ASC')->get();
            $right_bar = 0;
            $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav');
            return view('league.games')->with($data);
        } else {
            Session::flash('error_message', 'Your need to configure your league settings before accessing this section.');
            return redirect()->back();
        }
    }
    public function add_game()
    {
        $league_data = logged_in_league_data();
        if (!$league_data->schedule->isEmpty()) {
            $title = 'League Games';
            $nav = 'games';
            $league_data = logged_in_league_data();
            $locations = $league_data->location;
            $page_data = array();
            $right_bar = 1;
            $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'locations');
            return view('league.add_game')->with($data);
        } else {
            Session::flash('error_message', 'Your need to configure your league settings before accessing this section.');
            return redirect()->back();
        }
    }
    public function save_game(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'gamedate' => 'required|date',
            'gametime' => 'required',
            'gameHour' => 'required',
            'ampm' => 'required',
            'hometeam' => 'required',
            'awayteam' => 'required',
            'gamelocation' => 'required',
            'playersage' => 'required',
            'umpreqd' => 'required',
            'report' => 'required',
            'ump1pay' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'ump1bonus' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'ump234bonus' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ]
        ]);

        $validator->sometimes('ump234pay', ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'], function ($input) {
            return (int)$input->umpreqd > 1;
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {

            if ($request->ampm == 'PM') {
                if ($request->gametime != 12) {
                    $request->gametime += 12;
                }
            } else if ($request->ampm == 'AM' && $request->gametime == 12) {
                $request->gametime = 0;
            }


            $gamedatetime = Carbon::parse(date('Y-m-d', strtotime($request->gamedate)) . ' ' . $request->gametime . ':00:00');

            $displayGameTimeString = $request->gamedate . ' ' . $request->gameHour . ' ' . $request->ampm;
            $carbonDate = Carbon::createFromFormat('m/d/Y h:i A', $displayGameTimeString);
            $gamedate_toDisplay = $carbonDate->format('Y-m-d H:i:s');

            $currentDatetime = Carbon::now();

            if ($gamedatetime->lt($currentDatetime)) {
                $customErrors['gamedate'][] = 'Game Date/Time needs to be a future Date/Time';
                return response()->json(['errors' => $customErrors], 422);
            }

            $data = [
                'leagueid' => $league_data->leagueid,
                'gamedate' => $gamedatetime,
                'gamedate_toDisplay' => $gamedate_toDisplay,
                'playersage' => $request->playersage,
                'hometeamid' => $request->hometeam,
                'awayteamid' => $request->awayteam,
                'locid' => $request->gamelocation,
                'umpreqd' => $request->umpreqd,
                'report' => $request->report,
                'ump1pay' => $request->ump1pay,
                'ump1bonus' => $request->ump1bonus ?? 0,
                'ump234pay' => $request->ump234pay ?? 0,
                'ump234bonus' => $request->ump234bonus ?? 0
            ];
            GameModel::create($data);
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            return response()->json(['errors' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function edit_game($id)
    {
        $league_data = logged_in_league_data();
        if (!$league_data->schedule->isEmpty()) {
            $title = 'League Games';
            $nav = 'games';
            $league_data = logged_in_league_data();
            $locations = $league_data->location;
            $page_data = GameModel::find($id);
            $right_bar = 1;
            $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'locations');
            return view('league.add_game')->with($data);
        } else {
            Session::flash('error_message', 'Your need to configure your league settings before accessing this section.');
            return redirect()->back();
        }
    }
    public function update_game(Request $request, $id)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'gamedate' => 'required|date',
            'gametime' => 'required',
            'gameHour' => 'required',
            'ampm' => 'required',
            'hometeam' => 'required',
            'awayteam' => 'required',
            'gamelocation' => 'required',
            'playersage' => 'required',
            'umpreqd' => 'required',
            'report' => 'required',
            'ump1pay' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'ump1bonus' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'ump234bonus' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ]
        ]);

        $validator->sometimes('ump234pay', ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'], function ($input) {
            return (int)$input->umpreqd > 1;
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {

            if ($request->ampm == 'PM') {
                if ($request->gametime != 12) {
                    $request->gametime += 12;
                }
            } else if ($request->ampm == 'AM' && $request->gametime == 12) {
                $request->gametime = 0;
            }

            $gamedatetime = Carbon::parse(date('Y-m-d', strtotime($request->gamedate)) . ' ' . $request->gametime . ':00:00');

            $displayGameTimeString = $request->gamedate . ' ' . $request->gameHour . ' ' . $request->ampm;
            $carbonDate = Carbon::createFromFormat('m/d/Y h:i A', $displayGameTimeString);
            $gamedate_toDisplay = $carbonDate->format('Y-m-d H:i:s');

            $currentDatetime = Carbon::now();

            if ($gamedatetime->lt($currentDatetime)) {
                $customErrors['gamedate'][] = 'Game Date/Time needs to be a future Date/Time';
                return response()->json(['errors' => $customErrors], 422);
            }
            $data = [
                'leagueid' => $league_data->leagueid,
                'gamedate' => $gamedatetime,
                'gamedate_toDisplay' => $gamedate_toDisplay,
                'playersage' => $request->playersage,
                'hometeamid' => $request->hometeam,
                'awayteamid' => $request->awayteam,
                'locid' => $request->gamelocation,
                'umpreqd' => $request->umpreqd,
                'report' => $request->report,
                'ump1pay' => $request->ump1pay,
                'ump1bonus' => $request->ump1bonus ?? 0,
                'ump234pay' => $request->ump234pay ?? 0,
                'ump234bonus' => $request->ump234bonus ?? 0
            ];
            GameModel::find($id)->update($data);
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            return response()->json(['errors' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function delete_game($id)
    {
        try {
            $game = GameModel::find($id);
            $league = $game->league;
            $assigned_cols = [];
            for ($i = 1; $i <= (int)$game->umpreqd; $i++) {
                $col = 'ump' . $i;
                if ($game->{$col} !== null) {
                    $assigned_cols[] = $col;
                }
            }
            if (!empty($assigned_cols)) {
                foreach ($assigned_cols as $assigned_col) {
                    $umpid = $game->{$assigned_col};
                    $umpire = UmpireModel::find($umpid);
                    $leagueumpire = $league->umpires()->where('umpid', $umpid)->first();
                    refund_point_to_Aumpire($leagueumpire, $game->gameid);
                    try {
                        //notification mail
                        if ($umpire->email_settings->cancel_game == 1) {
                            $umpire_email = $umpire->user->email;
                            Mail::to($umpire_email)->send(new CancelGame($game, $umpire, $umpire_email));
                        }
                        //notification mail end
                        $msg = 'The scheduled game on ' . date('D m/d/y', strtotime($game->gamedate)) . ' has been canceled.';
                        add_notification($umpid, $msg, 4, 'ump');
                        Session::flash('message', 'Success');
                    } catch (\Throwable $th) {
                        Session::flash('error_message', 'Mail failed but umpire approved.');
                    }
                }
            }
            $game->delete();
            Session::flash('message', 'Success');
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something went wrong..!!');
        }
        return redirect()->back();
    }
    public function export_format()
    {
        $league_data = logged_in_league_data();
        $teams = $league_data->teams;
        $locations = $league_data->location;

        $team_data = array();
        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_data[] = [$team->teamid, $team->teamname];
            }
        }
        $location_data = array();
        if (!empty($locations)) {
            foreach ($locations as $location) {
                $location_data[] = [$location->locid, $location->ground];
            }
        }

        $demo_data[0] = ['2023-10-05 14:00:00', '1', '2', '3', '10', '2', 'yes', '100', '20', '70', '10'];
        $demo_data[1] = ['2023-11-15 17:00:00', '3', '4', '5', '15', '1', 'no', '230', '0', '0', '0'];

        // $export = new GameSheet(
        //     $team_data,
        //     $location_data,
        //     $demo_data
        // );

        // return Excel::download($export, 'game_insert_format.xlsx');


        $export1 = new AllSheets($demo_data, 'Game Import Format');
        $export2 = new AllSheets($team_data, 'Team Details');
        $export3 = new AllSheets($location_data, 'Location Details');

        $timestamp = now()->format('Y_m_d_His');

        // Export and store each file
        $file1 = "demoData_{$timestamp}.csv";
        $file2 = "teamData_{$timestamp}.csv";
        $file3 = "lcoationData_{$timestamp}.csv";
        $noteFile = "gameImportNote.txt";

        Excel::store($export1, $file1);
        Excel::store($export2, $file2);
        Excel::store($export3, $file3);

        // Create a zip archive
        $zipFilename = "GameImportFormatDetails.zip";
        $zip = new ZipArchive();
        $zip->open(storage_path("app/{$zipFilename}"), ZipArchive::CREATE);

        // Add files to the zip archive
        $zip->addFile(storage_path("app/{$file1}"), 'GameFormat.csv');
        $zip->addFile(storage_path("app/{$file2}"), 'TeamID.csv');
        $zip->addFile(storage_path("app/{$file3}"), 'LocationID.csv');
        $zip->addFile(storage_path("app/{$noteFile}"), 'NOTES.txt');

        // Close the zip archive
        $zip->close();

        // Delete the exported files
        Storage::delete([
            "{$file1}",
            "{$file2}",
            "{$file3}",
        ]);

        // Return the zip file as a download response
        return response()->download(storage_path("app/{$zipFilename}"))
            ->deleteFileAfterSend();
    }
    public function import_format(Request $request)
    {
        $league_data = logged_in_league_data();
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv',
        ]);

        if ($validator->fails()) {
            Session::flash('error_message', 'Only CSV format is allowed.');
            return redirect('league/games');
        }

        $file = $request->file('file');
        try {
            $import = new GameImport();
            Excel::import($import, $file);
            $importedData = $import->getData();
            array_shift($importedData);
            if (!empty($importedData)) {
                $flag = 0;
                foreach ($importedData as $data) {

                    $dataValidator = Validator::make($data, [
                        0 => 'required|date',
                        1 => 'required|numeric|min:1',
                        2 => 'required|numeric|min:1',
                        3 => 'required|numeric|min:1',
                        4 => 'required|numeric|min:1',
                        5 => 'required|numeric|min:1',
                        6 => 'required|in:yes,no',
                        7 => [
                            'required',
                            'numeric',
                            'regex:/^\d+(\.\d{1,2})?$/',
                        ],
                        8 => [
                            'nullable',
                            'numeric',
                            'regex:/^\d+(\.\d{1,2})?$/',
                        ],
                        10 => [
                            'nullable',
                            'numeric',
                            'regex:/^\d+(\.\d{1,2})?$/',
                        ]
                    ]);

                    if ($dataValidator->fails()) {
                        $flag++;
                    } else {
                        if ($league_data->report == 0) {
                            $data[6] = 'no';
                        } elseif ($league_data->report == 1) {
                            $data[6] = 'yes';
                        }

                        if ((int)$data[5] == 1) {
                            $data[9] = 0;
                            $data[10] = 0;
                        }
                        //adjusting gamedate to take only hour (HH:00:00)
                        $originalDate = $data[0];
                        $carbonDate = Carbon::parse($originalDate);
                        $carbonDate->setTime($carbonDate->hour, 0, 0);
                        $formattedDate = $carbonDate->format("Y-m-d H:i:s");
                        //adjusting gamedate to take only hour (HH:00:00)
                        $gameData = [
                            'leagueid' => $league_data->leagueid,
                            'gamedate' => $formattedDate,
                            'gamedate_toDisplay' => date('Y-m-d H:i:s', strtotime($originalDate)),
                            'playersage' => $data[4],
                            'hometeamid' => $data[1],
                            'awayteamid' => $data[2],
                            'locid' => $data[3],
                            'umpreqd' => $data[5],
                            'report' => $data[6] == 'yes' ? 1 : 0,
                            'ump1pay' => $data[7],
                            'ump1bonus' => $data[8] ?? 0,
                            'ump234pay' => $data[9] ?? 0,
                            'ump234bonus' => $data[10] ?? 0
                        ];
                        GameModel::create($gameData);
                    }
                }
                if ($flag > 0) {
                    Session::flash('error_message', 'Some rows are not formatted correctly..!!');
                } else {
                    Session::flash('message', 'Success');
                }
            } else {
                Session::flash('error_message', 'No data found.');
            }
            return redirect('league/games');
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something went wrong..!!');
        }
    }
    public function view_umpires()
    {
        $league_data = logged_in_league_data();
        $title = 'League Umpires';
        $nav = 'umpires';
        $page_data = $league_data->umpires;
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav');
        return view('league.umpires')->with($data);
    }
    public function view_new_applicants()
    {
        $league_data = logged_in_league_data();
        $title = 'League Umpires';
        $nav = 'umpires';
        $page_data = $league_data->umpire_apply()->whereIn('status', [0, 3])->get();
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav');
        return view('league.new_applicants')->with($data);
    }
    public function view_application($umpire_id)
    {
        $league_data = logged_in_league_data();
        $umpire = UmpireModel::find($umpire_id);
        $applications = $umpire->league_applications()->where('leagueid', $league_data->leagueid)->get();

        $output = '<div><div>NAME: ' . $umpire->name . '</div>';
        $output .= '<div>DOB: ' . date('m/d/Y', strtotime($umpire->dob)) . '</div>';
        $output .= '<div>ZIP: ' . $umpire->zip . '</div></div>';
        $output .= '<div>BIO: ' . $umpire->bio . '</div></div>';
        if ($applications->count() > 0) {
            foreach ($applications as $k => $application) {
                $output .= '
                <div class="modalqstnbalbe">
                <label class="qstn-label" for="">' . ($k + 1) . '. ' . $application->league_question->question . '</label>
                <div class="tetxtsbgs ans-forthetext">' . $application->answer . '</div>
            </div>
                            ';
            }
            $output .= '
            <div class="text-center submit-bten-modal">
            <a href="' . url('league/approve-umpire/' . $umpire_id) . '" class="application greenbtn withsd normalLinkLoader">Approve</a>

            <a href="' . url('league/decline-umpire/' . $umpire_id) . '" class="application redsbtn withsd normalLinkLoader">Decline</a>
            <a href="' . url('league/interview-umpire/' . $umpire_id) . '" class="application ylwsbtn withsd normalLinkLoader">Interview</a>
        </div>
            ';
        } else {
            dd();
        }
        echo $output;
    }
    public function approve_umpire($id)
    {
        $league_data = logged_in_league_data();
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
            //notification mail
            if ($umpire->email_settings->application == 1) {
                $umpire_email = $umpire->user->email;
                Mail::to($umpire_email)->send(new ApproveUmpireMail($league_data, $umpire, $umpire_email));
            }
            //notification mail end
            $msg = 'You joined ' . $league_data->leaguename;
            add_notification($id, $msg, 2, 'ump');
            Session::flash('message', 'Success');
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Mail failed but umpire approved.');
        }

        return redirect('league/umpires');
    }
    public function decline_umpire($id)
    {
        $league_data = logged_in_league_data();
        $umpire = UmpireModel::find($id);

        $update_data = [
            'status' => 2
        ];
        $umpire->applied_leagues()->where('leagueid', $league_data->leagueid)->update($update_data);
        try {
            //notification mail
            if ($umpire->email_settings->application == 1) {
                $umpire_email = $umpire->user->email;
                Mail::to($umpire_email)->send(new DeclineUmpireMail($league_data, $umpire, $umpire_email));
            }
            //notification mail end
            $msg = 'League application declined for ' . $league_data->leaguename;
            add_notification($id, $msg, 2, 'ump');
            Session::flash('message', 'Success');
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Mail failed.');
        }
        return redirect('league/view-new-applicants');
    }
    public function interview_umpire($id)
    {
        $league_data = logged_in_league_data();
        $umpire = UmpireModel::find($id);

        $update_data = [
            'status' => 3
        ];
        $umpire->applied_leagues()->where('leagueid', $league_data->leagueid)->update($update_data);
        try {
            //notification mail
            if ($umpire->email_settings->application == 1) {
                $umpire_email = $umpire->user->email;
                Mail::to($umpire_email)->send(new InterviewUmpireMail($league_data, $umpire, $umpire_email));
            }
            //notification mail end
            $msg = $league_data->leaguename . ' invited you for an interview.';
            add_notification($id, $msg, 2, 'ump');
            Session::flash('message', 'Success');
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Mail failed.');
        }
        return redirect('league/view-new-applicants');
    }
    public function manage_umpire_details($id)
    {
        $league_data = logged_in_league_data();
        $title = 'League Umpires';
        $nav = 'umpires';
        $page_data = UmpireModel::find($id);
        $league_umpire = $page_data->leagues()->where('leagueid', $league_data->leagueid)->first();
        if ($page_data->blocked_leagues()->where('leagueid', $league_data->leagueid)->first()) {
            $blocked = true;
        } else {
            $blocked = false;
        }
        $blocked_teams = $page_data->blocked_team()
            ->where('leagueid', $league_data->leagueid)
            ->get();
        $blocked_divisions = $page_data->blocked_division()
            ->where('leagueid', $league_data->leagueid)
            ->get();
        $blocked_grounds = $page_data->blocked_ground()
            ->where(function ($query) use ($league_data) {
                $query->where('leagueid', $league_data->leagueid)
                    ->orWhere('leagueid', 0);
            })
            ->get();
        $right_bar = 1;
        $teams = $league_data->teams;
        $locations = $league_data->location;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'league_umpire', 'locations', 'teams', 'blocked', 'blocked_teams', 'blocked_grounds', 'blocked_divisions');
        return view('league.manage_umpire')->with($data);
    }
    public function remove_umpire_from_league($id)
    {
        $league_data = logged_in_league_data();
        $res = removeUmpireFromLeague($id, $league_data->leagueid);
        if ($res['status']) {
            Session::flash('message', 'Success');
        } else {
            Session::flash('error_message', $res['error']);
        }
        return redirect('league/umpires');
    }
    public function pay_bonus(Request $request, $umpid)
    {
        $league_data = logged_in_league_data();
        $umpire_data = UmpireModel::find($umpid);
        $league_umpire = $umpire_data->leagues()->where('leagueid', $league_data->leagueid)->first();

        $validator = Validator::make($request->all(), [
            'paydate' => 'required|date',
            'payamt' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0]);
        }
        $owe = $league_umpire->owed;
        $bonus = $league_umpire->bonus ?? 0;
        $bonus += (float)$request->payamt;
        $owe += (float)$request->payamt;

        $leagueumpire_data = [
            'owed' => $owe,
            'bonus' => $bonus,
        ];
        if ($league_umpire->update($leagueumpire_data)) {
            if (add_payRecord($league_data->leagueid, $umpid, $request->paydate, $request->payamt, 'adjusted')) {
                Session::flash('message', 'Success');
                return response()->json(['status' => 1]);
            }
        }
        return response()->json(['status' => 0]);
    }
    public function blockUnblock_umpire($umpid)
    {
        $league_data = logged_in_league_data();
        $umpire_data = UmpireModel::find($umpid);
        $row = $umpire_data->blocked_leagues()->where('leagueid', $league_data->leagueid)->first();
        if ($row) {
            $leagueUmpiredata = ['status' => 0];
            $row->delete();
        } else {
            $leagueUmpiredata = ['status' => 1];
            $data = [
                'leagueid' => $league_data->leagueid,
                'umpid' => $umpid,
            ];
            BlockUmpireModel::create($data);
        }
        $umpire_league_row = $league_data->umpires()->where('umpid', $umpid)->first();
        $umpire_league_row->update($leagueUmpiredata);
        Session::flash('message', 'Success');
        return redirect()->back();
    }
    public function save_point(Request $request, $umpid)
    {
        $league_data = logged_in_league_data();
        $umpire_data = UmpireModel::find($umpid);
        $league_umpire = $umpire_data->leagues()->where('leagueid', $league_data->leagueid)->first();

        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0]);
        }
        try {
            $leagueumpire_data = [
                'points' => $request->points
            ];
            $league_umpire->update($leagueumpire_data);
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0]);
        }
    }
    public function save_leagueumpire(Request $request, $umpid)
    {
        $league_data = logged_in_league_data();
        $umpire_data = UmpireModel::find($umpid);
        $league_umpire = $umpire_data->leagues()->where('leagueid', $league_data->leagueid)->first();

        $validator = Validator::make($request->all(), [
            'payout' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0]);
        }
        try {
            $leagueumpire_data = [
                'payout' => $request->payout,
                'notes' => $request->notes
            ];
            $league_umpire->update($leagueumpire_data);
            Session::flash('message', 'Success');
            return response()->json(['status' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0]);
        }
    }
    public function block_unblock_team(Request $request, $umpid)
    {
        $league_data = logged_in_league_data();
        $umpire_data = UmpireModel::find($umpid);

        $validator = Validator::make($request->all(), [
            'team_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0], 200);
        }
        $row = $umpire_data->blocked_team()
            ->where('leagueid', $league_data->leagueid)
            ->where('teamid', $request->team_id)
            ->first();
        if ($row) {
            $row->delete();
            $flag = true;
        } else {
            $data = [
                'leagueid' => $league_data->leagueid,
                'umpid' => $umpid,
                'teamid' => $request->team_id,
            ];
            BlockTeamModel::create($data);
            $flag = false;
        }
        Session::flash('message', 'Success');
        if ($flag) {
            return redirect()->back();
        } else {
            return response()->json(['status' => 1], 200);
        }
    }
    public function block_unblock_division(Request $request, $umpid)
    {
        $league_data = logged_in_league_data();
        $umpire_data = UmpireModel::find($umpid);

        $validator = Validator::make($request->all(), [
            'divid' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0], 200);
        }
        $row = $umpire_data->blocked_division()
            ->where('leagueid', $league_data->leagueid)
            ->where('divid', $request->divid)
            ->first();
        if ($row) {
            $row->delete();
            $flag = true;
        } else {
            $data = [
                'leagueid' => $league_data->leagueid,
                'umpid' => $umpid,
                'divid' => $request->divid,
            ];
            BlockDivisionModel::create($data);
            $flag = false;
        }
        Session::flash('message', 'Success');
        if ($flag) {
            return redirect()->back();
        } else {
            return response()->json(['status' => 1], 200);
        }
    }
    public function block_unblock_ground(Request $request, $umpid)
    {
        $league_data = logged_in_league_data();
        $umpire_data = UmpireModel::find($umpid);

        $validator = Validator::make($request->all(), [
            'location_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0], 200);
        }
        $row = $umpire_data->blocked_ground()
            ->where('leagueid', $league_data->leagueid)
            ->where('locid', $request->location_id)
            ->first();
        if ($row) {
            $row->delete();
            $flag = true;
        } else {
            $data = [
                'leagueid' => $league_data->leagueid,
                'umpid' => $umpid,
                'locid' => $request->location_id,
            ];
            BlockGroundModel::create($data);
            $flag = false;
        }
        Session::flash('message', 'Success');
        if ($flag) {
            return redirect()->back();
        } else {
            return response()->json(['status' => 1], 200);
        }
    }
    public function view_payout()
    {
        $league_data = logged_in_league_data();
        $title = 'League Payout';
        $nav = 'payout';
        $page_data = $league_data->umpires;
        $right_bar = 0;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav');
        return view('league.payout')->with($data);
    }
    public function pay_all()
    {
        $league_data = logged_in_league_data();
        $umpires = $league_data->umpires()->where('owed', '>', 0)->get();
        if ($umpires->count() > 0) {
            foreach ($umpires as $umpire) {
                $owe = $umpire->owed;
                $received = $umpire->received ?? 0;

                $amount = (float)$owe;
                $paydate = date('Y-m-d');

                $new_owe = $owe - $amount;
                $received += $amount;

                $umpire->owed = $new_owe;
                $umpire->received = $received;

                if ($umpire->save()) {
                    if ($amount > 0) {
                        add_payRecord($umpire->leagueid, $umpire->umpid, $paydate, $amount, 'payout');
                    }
                }
            }
        }
        Session::flash('message', 'Success');
        return redirect()->back();
    }
    public function view_payout_history($leagueid, $umpid)
    {
        $payouts = PayoutModel::where('leagueid', $leagueid)
            ->where('umpid', $umpid)
            ->orderBy('id', 'ASC')
            ->get();
        $output = '';
        if (!$payouts->isEmpty()) {
            foreach ($payouts as $payout) {
                if ($payout->pmttype == 'adjusted') {
                    $type = '<span class="text-success">Adjusted</span>';
                } elseif ($payout->pmttype == 'payout') {
                    $type = '<span class="text-danger">Payout</span>';
                } else {
                    $type = '<span class="">Game</span>';
                }
                $date = date('D m/d/y', strtotime($payout->paydate));

                $output .= '<tr>';
                $output .= '<td>' . $type . '</td>';
                $output .= '<td class="time-table">' . $date . '</td>';
                if ($payout->payamt < 0) {
                    $output .= '<td>-$ ' . str_replace('-', '', $payout->payamt) . '</td>';
                } else {
                    $output .= '<td>$ ' . $payout->payamt . '</td>';
                }
                $output .= '<td>$ ' . $payout->owe . '</td>';
                if ($payout->pmttype !== 'game') {
                    $output .= '<td class="text-danger"><a href="' . url('league/delete-payout/' . $payout->id) . '" onclick="return confirm(\'Are you sure?\')"><i class="fa-regular fa-trash-can"></i></a></td>';
                }
                $output .= '</tr>';
            }
        }
        echo $output;
    }
    public function payout(Request $request)
    {
        $leagueumpire_id = (int)$request->leagueumpire_id;
        $leagueumpire = LeagueUmpireModel::find($leagueumpire_id);
        $owe = $leagueumpire->owed;
        $received = $leagueumpire->received ?? 0;
        $bonus = $leagueumpire->bonus ?? 0;
        $rules = [
            'leagueumpire_id' => 'required|integer',
            'amount' => [
                'nullable',
                'required_without:bonus_amount',
                'numeric',
                'gt:0',
                'lte:' . $owe,
            ],
            'bonus_amount' => [
                'nullable',
                'required_without:amount',
                'numeric',
                // 'gt:0',
            ],
            'paydate' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        //`bonus` was prev name, `adjusted` is new.

        $bonus_amount = (float)$request->bonus_amount;

        $amount = (float)$request->amount;
        $paydate = date('Y-m-d', strtotime($request->paydate));

        $new_owe = ($owe - $amount) + $bonus_amount;
        $received += $amount;
        $bonus += $bonus_amount;

        $leagueumpire->owed = $new_owe;
        $leagueumpire->received = $received;
        $leagueumpire->bonus = $bonus;

        if ($leagueumpire->save()) {
            if ($amount > 0) {
                add_payRecord($leagueumpire->leagueid, $leagueumpire->umpid, $paydate, $amount, 'payout');
            }
            // if ($bonus_amount > 0) {
            add_payRecord($leagueumpire->leagueid, $leagueumpire->umpid, $paydate, $bonus_amount, 'adjusted');
            // }
            return response()->json(['message' => 'Success', 'new_owe' => $new_owe, 'new_received' => $received], 200);
        } else {
            return response()->json([
                'errors' => '<p>Something went wrong..!!</p>',
            ], 422);
        }
    }
    public function delete_payout($id)
    {
        $payout_row = PayoutModel::findOrFail($id);
        $payout_id = $payout_row->id;
        $leagueid = $payout_row->leagueid;
        $umpid = $payout_row->umpid;
        $payamt = (float)$payout_row->payamt;
        $pmttype = $payout_row->pmttype;
        // if ($pmttype !== 'game') {
        if ($payout_row->delete()) {
            $leagueumpire = LeagueUmpireModel::where('leagueid', $leagueid)
                ->where('umpid', $umpid)
                ->first();
            $related_rows = PayoutModel::where('leagueid', $leagueid)
                ->where('umpid', $umpid)
                ->where('id', '>', $payout_id)
                ->get();
            if ($pmttype == 'payout') {
                $leagueumpire->owed += $payamt;
                $leagueumpire->received -= $payamt;

                if (!$related_rows->isEmpty()) {
                    foreach ($related_rows as $related_row) {
                        $new_owe = (float)$related_row->owe + $payamt;
                        $related_row->update(['owe' => $new_owe]);
                    }
                }
            } elseif ($pmttype == 'adjusted' || $pmttype == 'game') {
                $leagueumpire->bonus -= (float)$payamt;
                $leagueumpire->owed -= $payamt;

                if (!$related_rows->isEmpty()) {
                    foreach ($related_rows as $related_row) {
                        $new_owe = (float)$related_row->owe - $payamt;
                        $related_row->update(['owe' => $new_owe]);
                    }
                }
            }
            $leagueumpire->save();
            Session::flash('message', 'Success');
        } else {
            Session::flash('error_message', 'Something went wrong..!!');
        }
        // }
        //  else {
        //     Session::flash('error_message', 'Game payouts can\'t be deleted..!!');
        // }
        return redirect()->back();
    }
    public function view_change_password()
    {
        $title = 'League Change Password';
        $nav = '';
        $league_data = logged_in_league_data();
        $right_bar = 1;
        $data = compact('title', 'league_data', 'right_bar', 'nav');
        return view('league.change_password')->with($data);
    }
    public function view_notifications()
    {
        $title = 'League Notifications';
        $nav = '';
        $league_data = logged_in_league_data();
        $email_settings = $league_data->email_settings;
        $page_data = get_notifications($league_data->leagueid, 2);
        $right_bar = 1;
        $data = compact('title', 'page_data', 'league_data', 'right_bar', 'nav', 'email_settings');
        return view('league.notifications')->with($data);
    }
    public function save_email_settings(Request $request)
    {
        $league_data = logged_in_league_data();
        try {
            $data = [
                'join_game' => $request->join_game ? 1 : 0,
                'leave_game' => $request->leave_game ? 1 : 0,
                'apply' => $request->apply ? 1 : 0,
            ];
            $league_data->email_settings()->update($data);
            Session::flash('message', 'Success');
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something Went Wrong..!!');
        }
        return redirect()->back();
    }
    public function manual_assign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'umpid' => 'required|numeric',
            'pos' => 'required',
            'gameid' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0], 200);
        }
        $umpid = $request->input('umpid');
        $pos = $request->input('pos');
        $gameid = $request->input('gameid');

        $umpire = UmpireModel::findOrFail($umpid);
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
                $game_divisionsRows = array($game->hometeam->division, $game->awayteam->division);

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
                                    Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on the game time.');
                                }
                            } else {
                                $condition_met = false; // Set the flag to true if date is blocked
                                break; // Exit this loop
                            }
                        } else {
                            $condition_met = true; // Set the flag to true if time is blocked
                            Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on the game time');
                        }
                    }
                } else {
                    $condition_met = true; // Set the flag to true if time is blocked
                    Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on the game time.');
                }

                // Checking umpire's blocked grounds
                $blocked_grounds = $umpire->blocked_ground;
                foreach ($blocked_grounds as $blocked_ground) {
                    if ($blocked_ground->locid == $game->locid) {
                        $condition_met = true; // Set the flag to true if ground is blocked
                        Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on : ' . htmlspecialchars($blocked_ground->ground->ground));
                        break; // Exit this loop
                    }
                }

                // Checking umpire's blocked divisions
                $blocked_divisions = $umpire->blocked_division;
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
                            Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' blocked from games which includes Division: ' . htmlspecialchars($blocked_division->division->name));
                            break; // Exit this loop
                        }
                    }
                }

                // Checking umpire's blocked teams
                $blocked_teams = $umpire->blocked_team;
                foreach ($blocked_teams as $blocked_team) {
                    if (in_array($blocked_team->teamid, $game_teams)) {
                        $condition_met = true; // Set the flag to true if team is blocked
                        Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' blocked from games which includes Team: ' . htmlspecialchars($blocked_team->team->teamname));
                        break; // Exit this loop
                    }
                }
                //checking umpire age
                $game_player_age = (int)$game->playersage;
                $age_diff = $umpire_age - $game_player_age;
                if ($pos == 'ump1') {
                    if ($age_diff < $mainumpage) {
                        $condition_met = true; // Set the flag to true if age diff is lower
                        Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' don\'t meet the game\'s age requirement.');
                    }
                } else {
                    if ($age_diff < $otherumpage) {
                        $condition_met = true; // Set the flag to true if age diff is lower
                        Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' don\'t meet the game\'s age requirement.');
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
                    if ($this->assign_ump_toAgamePosition($gameid, $pos, $umpid, 1)) {
                        Session::flash('message', 'Success');
                        $msg = 'New game assigned on ' . date('D m/d/y', strtotime($game->gamedate));
                        add_notification($umpid, $msg, 4, 'ump');
                        $res = ['status' => 1];
                    } else {
                        Session::flash('error_message', 'Something went wrong..!!');
                        $res = ['status' => 0];
                    }
                } else {
                    if ($gapMorethnTwo == 1) {
                        $res = ['status' => 2, 'gameid' => $gameid, 'pos' => $pos, 'umpid' => $umpid]; //for same gamedate
                    } elseif ($gapMorethnTwo == 2) {
                        $res = ['status' => 0];
                        Session::flash('error_message', 'Difference between umpire\'s assigned games are less than 2 hours.');
                    }
                }
            } else {
                Session::flash('error_message', 'Umpire: ' . htmlspecialchars($umpire->name) . ' blocked from this league.');
                $res = ['status' => 0];
            }
        } else {
            Session::flash('error_message', 'Umpire assigning on this game hasn\'t been started yet.');
            $res = ['status' => 0];
        }
        return response()->json($res);
    }
    public function assign_ump_toAgamePosition($gameid, $pos, $umpid, $returnType = 0)
    {
        try {
            $umpire = UmpireModel::findOrFail($umpid);
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
                    try {
                        Mail::to($league_admin_email)->send(new ScheduleGame($league, $umpire, $game, 'league', $league_admin_email));
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
            if ($returnType == 1) {
                return true;
            } else {
                Session::flash('message', 'Success');
                return redirect()->back();
            }
        } catch (Exception $e) {
            // dd($e);
        }
    }
    public function remove_umpire(int $gameid, $pos)
    {
        try {
            $game = GameModel::findOrFail($gameid);
            $umpid = $game->{$pos};
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
                $msg = 'League removed you from a game on ' . date('D m/d/y', strtotime($game->gamedate));
                add_notification($umpid, $msg, 6, 'ump');
                Session::flash('message', 'Success');
            }
        } catch (\Throwable $th) {
            Session::flash('error_message', 'Something went wrong..!!');
        }
        return redirect()->back();
    }
    public function delete_league_admin($id)
    {
        try {
            $row = UserModel::where('usertype', 2)->where('uid', $id)->firstOrFail();
            if ($row->isLeagueOwner == 1) {
                $league = $row->league;
                $upcoming_games_check = $league->games()->whereDate('gamedate', '>=', today())
                    ->count();
                if ($upcoming_games_check > 0) {
                    Session::flash('error_message', 'League can not be deleted due to having upcoming games.');
                    return redirect()->back();
                }
                $league->umpires()->delete();
                $league->delete();
                $row->delete();
                Session::flash('message', 'Success');
            } else {
                $row->delete();
                Session::flash('message', 'Success');
            }
        } catch (\Throwable $th) {
            //throw $th;
            Session::flash('error_message', 'Something went wrong.');
        }
        return redirect()->back();
    }
    public function leagueRunningSchedulerManually()
    {
        $league_data = logged_in_league_data();
        $title = 'Auto schedule';
        $nav = 'auto_algo';
        $right_bar = 0;
        $data = compact('title', 'league_data', 'right_bar', 'nav');
        return view('league.manual_schedual')->with($data);
    }
}
