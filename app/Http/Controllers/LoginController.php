<?php

namespace App\Http\Controllers;

use App\Models\LeagueModel;
use App\Models\UserModel;
use App\Models\UmpireModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function viewAdminLogin()
    {
        $title = 'Login';
        $data = compact('title');
        return view('admin.login')->with($data);
    }
    public function verifyAdminLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $user = UserModel::where('email', $username)
            ->where('usertype', 1)
            ->first();
        if ($user) {
            $hashedPassword = $user->password;
            if (Hash::check($password, $hashedPassword)) {
                session(['admin_data' => $user]);
                return redirect('/admin/league');
            } else {
                return redirect()->back()->withErrors(['loginError' => 'Wrong Password']);
            }
        } else {
            return redirect()->back()->withErrors(['loginError' => 'Wrong Username']);
        }
    }
    public function league_login()
    {
        $title = '- League Login';
        $data = compact('title');
        return view('general.league_login')->with($data);
    }
    public function league_signup($ency_email)
    {
        $email = Crypt::decryptString($ency_email);
        $title = '- League Signup';
        $data = compact('title', 'email');
        return view('general.league_signup')->with($data);
    }
    public function league_admin_signup($ency_email, $ency_id)
    {
        $email = Crypt::decryptString($ency_email);
        $leagueid = Crypt::decryptString($ency_id);
        $league = LeagueModel::find($leagueid);
        $title = '- League Admin Signup';
        $data = compact('title', 'email', 'league');
        return view('general.league_admin_signup')->with($data);
    }
    public function verifyLeagueLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $user = UserModel::where('email', $username)
            ->where('usertype', 2)
            ->first();
        if ($user) {
            $hashedPassword = $user->password;
            if (Hash::check($password, $hashedPassword)) {
                if ((int)$user->status == 1) {
                    if ((int)$user->league->status == 1) {
                        session(['league_data' => $user]);
                        return redirect('/league');
                    } else {
                        Session::flash('error_message', 'League Deactivated.');
                        return redirect()->back();
                    }
                } else {
                    Session::flash('error_message', 'Account Deactivated.');
                    return redirect()->back();
                }
            } else {
                Session::flash('error_message_psw', 'Wrong Password.');
                return redirect()->back();
            }
        } else {
            Session::flash('error_message_uname', 'Wrong Email.');
            return redirect()->back();
        }
    }
    public function umpire_signup()
    {
        $title = '- Umpire Signup';
        $data = compact('title');
        return view('general.umpire_signup')->with($data);
    }
    public function umpire_login()
    {
        $title = '- Umpire Login';
        $data = compact('title');
        return view('general.umpire_login')->with($data);
    }
    public function verifyUmpireLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $user = UserModel::where('email', $username)
            ->where('usertype', 3)
            ->first();
        if ($user) {
            $hashedPassword = $user->password;
            if (Hash::check($password, $hashedPassword)) {
                if ($user->umpire->email_verify_status == 1) {
                    if ((int)$user->umpire->status == 1) {
                        session(['umpire_data' => $user]);
                    } else {
                        Session::flash('error_message', 'Account Deactivated.');
                        return redirect()->back();
                    }
                    return redirect('/umpire');
                } else {
                    Session::flash('error_message', 'Please verify your email first.');
                    return redirect()->back();
                }
            } else {
                Session::flash('error_message_psw', 'Wrong Password.');
                return redirect()->back();
            }
        } else {
            Session::flash('error_message_uname', 'Wrong Email.');
            return redirect()->back();
        }
    }
    public function directLogin()
    {
        $row = UserModel::find(1);
        session(['admin_data' => $row]);
        return redirect('admin');
    }
    public function adminLogout()
    {
        session()->forget('admin_data');
        return redirect('/admin/login');
    }
    public function leagueLogout()
    {
        session()->forget('league_data');
        return redirect('league-login');
    }
    public function umpireLogout()
    {
        session()->forget('umpire_data');
        return redirect('umpire-login');
    }
    public function change_password(Request $request, $userId)
    {
        if ($user = UserModel::find($userId)) {
            $validator = Validator::make($request->all(), [
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
                ],
                'old_password' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => 'Validation error'], 200);
            }

            $old_password = $request->old_password;
            $new_password = $request->password;
            $hashedPassword = $user->password;

            if (Hash::check($old_password, $hashedPassword)) {
                $user_data = [
                    'password' => Hash::make($new_password),
                ];
                $user->update($user_data);
                return response()->json(['status' => 1], 200);
            } else {
                return response()->json(['status' => 0, 'message' => 'Wrong old password'], 200);
            }
        }
    }
}
