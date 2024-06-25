<?php

use App\Http\Controllers\DemoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\UmpireController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//DEMO SITE
Route::get('/demo-umpire', [DemoController::class, 'demoUmpire']);
Route::get('/demo-league', [DemoController::class, 'demoLeague']);

//test
// Route::get('/game-auto-schedule', [GeneralController::class, 'game_auto_schedule']);
// Route::get('/after-game', [GeneralController::class, 'afterGame']);
// Route::get('/test', [GeneralController::class, 'test']);

Route::middleware(['demoCheck'])->group(function () {
    //general
    Route::get('/', [GeneralController::class, 'index']);
    Route::get('/advertisement', [GeneralController::class, 'viewAdvertisement']);
    Route::post('/contact', [GeneralController::class, 'submitContact']);
    Route::post('/forget-password', [GeneralController::class, 'forget_password']);
    Route::post('/send-feedback', [GeneralController::class, 'send_feedback']);
    Route::get('/reset-password/{encid}', [GeneralController::class, 'reset_password']);
    Route::post('/reset-password/{id}', [GeneralController::class, 'change_password']);
    Route::get('/verify-otp/{id}', [GeneralController::class, 'verifyOTP']);
    Route::get('/resend-otp/{id}', [GeneralController::class, 'resendOTP']);
    Route::post('/verify-otp/{id}', [GeneralController::class, 'checkOTP']);
    Route::post('/change-password/{id}', [LoginController::class, 'change_password']);
    Route::get('/delete/notification/{id}', [GeneralController::class, 'delete_notification']);
    Route::get('/privacy-policy', [GeneralController::class, 'privacy_policy']);
    Route::get('/terms-of-use', [GeneralController::class, 'terms_of_use']);
    Route::get('/unsubscribe/{encMail}', [GeneralController::class, 'unsubMail']);
    //league
    Route::get('/league-login', [LoginController::class, 'league_login']);
    Route::post('/league-login', [LoginController::class, 'verifyLeagueLogin']);
    Route::get('/league-admin-signup/{email}/{id}', [LoginController::class, 'league_admin_signup']);
    Route::post('/league-admin-signup', [LeagueController::class, 'saveLeagueAdmin']);
    Route::get('/league-signup/{email}', [LoginController::class, 'league_signup']);
    Route::post('/league-signup', [LeagueController::class, 'saveLeague']);
    Route::middleware(['leagueLoginCheck'])->group(function () {
        Route::get('/league', [LeagueController::class, 'league_view']);
        Route::get('/league/view-report/{id}/{col}', [LeagueController::class, 'view_report']);
        Route::get('/league/report-absent/{gameid}/{column}/{umpid}', [LeagueController::class, 'reportAbsent']);
        Route::get('/league/logout', [LoginController::class, 'leagueLogout']);
        Route::get('/league/change-password', [LeagueController::class, 'view_change_password']);
        //general
        Route::get('/league/settings', [LeagueController::class, 'view_settings']);
        Route::post('/league/save_general_settings', [LeagueController::class, 'save_general_settings']);
        //report
        Route::get('/league/settings/report', [LeagueController::class, 'view_report_settings']);
        Route::get('/league/delete_report/{id}', [LeagueController::class, 'delete_report']);
        Route::post('/league/save_report_question', [LeagueController::class, 'save_report_question']);
        Route::post('/league/update_report_question/{id}', [LeagueController::class, 'update_report_question']);
        Route::post('/league/save_report_settings', [LeagueController::class, 'save_report_settings']);
        Route::post('/league/update_report_order', [LeagueController::class, 'update_report_order']);
        //notification
        Route::get('/league/settings/notification', [LeagueController::class, 'view_notification_settings']);
        Route::post('/league/send_notification', [LeagueController::class, 'send_notification']);
        Route::get('/league/notifications', [LeagueController::class, 'view_notifications']);
        Route::post('/league/save-email-settings', [LeagueController::class, 'save_email_settings']);
        //application
        Route::get('/league/settings/application', [LeagueController::class, 'view_league_application_settings']);
        Route::post('/league/save_league_application_question', [LeagueController::class, 'save_league_application_question']);
        Route::post('/league/update_league_application_question/{id}', [LeagueController::class, 'update_league_application_question']);
        Route::post('/league/update_league_application_order', [LeagueController::class, 'update_league_application_order']);
        Route::get('/league/delete_application/{id}', [LeagueController::class, 'delete_application']);
        //division
        Route::get('/league/settings/divisions', [LeagueController::class, 'view_division']);
        Route::get('/league/delete_division/{id}', [LeagueController::class, 'delete_division']);
        Route::post('/league/save_division', [LeagueController::class, 'save_division']);
        Route::post('/league/update_division/{id}', [LeagueController::class, 'update_division']);
        //team
        Route::get('/league/settings/teams', [LeagueController::class, 'view_team']);
        Route::get('/league/delete_team/{id}', [LeagueController::class, 'delete_team']);
        Route::post('/league/save_team', [LeagueController::class, 'save_team']);
        Route::post('/league/update_team/{id}', [LeagueController::class, 'update_team']);
        //location
        Route::get('/league/settings/location', [LeagueController::class, 'view_location']);
        Route::get('/league/delete_location/{id}', [LeagueController::class, 'delete_location']);
        Route::post('/league/save_location', [LeagueController::class, 'save_location']);
        Route::post('/league/update_location/{id}', [LeagueController::class, 'update_location']);
        //features
        Route::get('/league/settings/features', [LeagueController::class, 'view_feature']);
        //point
        Route::redirect('/league/settings/points', url('/league/settings/point/schedule-on-any-game'));
        Route::get('/league/settings/point/{type}', [LeagueController::class, 'view_point']);
        Route::post('/league/save_base_point', [LeagueController::class, 'save_base_point']);
        Route::post('/league/save_age_of_players', [LeagueController::class, 'save_age_of_players']);
        Route::post('/league/save_location_preset', [LeagueController::class, 'save_location_preset']);
        Route::post('/league/save_pay', [LeagueController::class, 'save_pay']);
        Route::post('/league/save_time', [LeagueController::class, 'save_time']);
        Route::post('/league/save_umpire_duration', [LeagueController::class, 'save_umpire_duration']);
        Route::post('/league/save_total_game', [LeagueController::class, 'save_total_game']);
        Route::post('/league/save_umpire_position', [LeagueController::class, 'save_umpire_position']);
        Route::post('/league/save_day_of_week', [LeagueController::class, 'save_day_of_week']);
        //set_preset
        Route::post('/league/set_preset', [LeagueController::class, 'set_preset']);
        //games
        Route::get('/league/games', [LeagueController::class, 'view_games']);
        Route::get('/league/add-game', [LeagueController::class, 'add_game']);
        Route::post('/league/add-game', [LeagueController::class, 'save_game']);
        Route::get('/league/edit-game/{id}', [LeagueController::class, 'edit_game']);
        Route::post('/league/edit-game/{id}', [LeagueController::class, 'update_game']);
        Route::get('/league/delete-game/{id}', [LeagueController::class, 'delete_game']);
        Route::get('/league/export-format', [LeagueController::class, 'export_format']);
        Route::post('/league/import-format', [LeagueController::class, 'import_format']);
        Route::post('/league/manual-assign', [LeagueController::class, 'manual_assign']);
        Route::get('/league/same-game-assign/{id}/{pos}/{return?}', [LeagueController::class, 'assign_ump_toAgamePosition']);
        Route::get('/league/remove-umpire/{id}/{pos}', [LeagueController::class, 'remove_umpire']);
        //umpires
        Route::get('/league/umpires', [LeagueController::class, 'view_umpires']);
        Route::get('/league/view-new-applicants', [LeagueController::class, 'view_new_applicants']);
        Route::get('/league/view-application/{id}', [LeagueController::class, 'view_application']);
        Route::get('/league/approve-umpire/{id}', [LeagueController::class, 'approve_umpire']);
        Route::get('/league/decline-umpire/{id}', [LeagueController::class, 'decline_umpire']);
        Route::get('/league/interview-umpire/{id}', [LeagueController::class, 'interview_umpire']);
        Route::get('/league/manage-umpire/{id}', [LeagueController::class, 'manage_umpire_details']);
        Route::get('/league/remove-umpire-from-league/{id}', [LeagueController::class, 'remove_umpire_from_league']);
        Route::post('/league/pay-bonus/{id}', [LeagueController::class, 'pay_bonus']);
        Route::post('/league/save-point/{id}', [LeagueController::class, 'save_point']);
        Route::post('/league/save-leagueumpire/{id}', [LeagueController::class, 'save_leagueumpire']);
        Route::post('/league/block-unblock-team/{id}', [LeagueController::class, 'block_unblock_team']);
        Route::post('/league/block-unblock-division/{id}', [LeagueController::class, 'block_unblock_division']);
        Route::post('/league/block-unblock-ground/{id}', [LeagueController::class, 'block_unblock_ground']);
        Route::get('/league/block-unblock-umpire/{id}', [LeagueController::class, 'blockUnblock_umpire']);
        //payout
        Route::get('/league/payout', [LeagueController::class, 'view_payout']);
        Route::get('/league/pay-all', [LeagueController::class, 'pay_all']);
        Route::get('/league/view-payout-history/{leagueid}/{umpid}', [LeagueController::class, 'view_payout_history']);
        Route::post('/league/payout', [LeagueController::class, 'payout']);
        Route::get('/league/delete-payout/{id}', [LeagueController::class, 'delete_payout']);
        //manual auto-assign
        Route::get('/league/game-manual-schedule', [LeagueController::class, 'leagueRunningSchedulerManually']);
    });
    //umpire
    Route::get('/umpire-signup', [LoginController::class, 'umpire_signup']);
    Route::post('/umpire-signup', [UmpireController::class, 'saveUmpire']);
    Route::get('/umpire-login', [LoginController::class, 'umpire_login']);
    Route::post('/umpire-login', [LoginController::class, 'verifyUmpireLogin']);
    Route::middleware(['umpireLoginCheck'])->group(function () {
        Route::get('/umpire', [UmpireController::class, 'umpire_view']);
        Route::get('/umpire/show-reports', [UmpireController::class, 'showReport']);
        Route::get('/umpire/logout', [LoginController::class, 'umpireLogout']);
        Route::get('/umpire/change-password', [UmpireController::class, 'view_change_password']);
        Route::get('/umpire/leagues', [UmpireController::class, 'view_leagues']);
        Route::get('/umpire/leave-league/{id}', [UmpireController::class, 'leave_league']);
        Route::get('/umpire/apply-league/{id}', [UmpireController::class, 'apply_league']);
        Route::post('/umpire/apply-league', [UmpireController::class, 'save_league_apply']);
        Route::get('/umpire/avail/{id?}', [UmpireController::class, 'view_avail']);
        Route::post('/umpire/dateAvailInfo', [UmpireController::class, 'dateAvailInfo']);
        Route::post('/umpire/makeAvailUnavail', [UmpireController::class, 'makeAvailUnavail']);
        Route::post('/umpire/save_avail', [UmpireController::class, 'save_avail']);
        Route::post('/umpire/save-email-settings', [UmpireController::class, 'save_email_settings']);
        Route::get('/umpire/notifications', [UmpireController::class, 'view_notifications']);
        Route::get('/umpire/profile', [UmpireController::class, 'view_profile']);
        Route::post('/umpire/save-profile', [UmpireController::class, 'save_profile']);
        Route::post('/umpire/block-unblock-ground', [UmpireController::class, 'block_unblock_ground']);
        Route::post('/umpire/update-umpire-pref', [UmpireController::class, 'update_umpire_pref']);
        Route::get('/umpire/league-games/{id}', [UmpireController::class, 'league_games']);
        Route::get('/umpire/assign-to-game/{id}/{pos}', [UmpireController::class, 'manual_assign']);
        Route::get('/umpire/same-game-assign/{id}/{pos}/{return?}', [UmpireController::class, 'assign_ump_toAgamePosition']);
        Route::get('/umpire/games', [UmpireController::class, 'view_games']);
        Route::get('/umpire/cancel-game/{id}', [UmpireController::class, 'cancel_game']);
        Route::get('/umpire/submit-report/{id}', [UmpireController::class, 'submit_report']);
        Route::post('/umpire/submit-report', [UmpireController::class, 'save_report']);
        Route::get('/umpire/earning', [UmpireController::class, 'view_earnings']);
        Route::get('/umpire/barchart', [UmpireController::class, 'barchart']);
        Route::get('/umpire/piechart', [UmpireController::class, 'piechart']);
        Route::get('/umpire/report-absent/{gameid}/{column}', [UmpireController::class, 'reportAbsent']);
    });
    //admin
    Route::get('/admin/login', [LoginController::class, 'viewAdminLogin']);
    Route::post('/admin/login', [LoginController::class, 'verifyAdminLogin']);
    Route::get('/admin/direct-login', [LoginController::class, 'directLogin']);
    Route::middleware(['adminLoginCheck'])->group(function () {
        Route::redirect('/admin', url('/admin/league'));
        Route::get('/admin/logout', [LoginController::class, 'adminLogout']);
        Route::get('/admin/change-password/{id}', [GeneralController::class, 'view_admin_change_password']);
        //league
        Route::get('/admin/league', [LeagueController::class, 'view_league']);
        Route::get('/admin/delete-league-admin/{id}', [LeagueController::class, 'delete_league_admin']);
        Route::get('/admin/admin-list', [LeagueController::class, 'view_league_admin']);
        Route::get('/admin/add_league', [LeagueController::class, 'addEditLeague']);
        Route::post('/admin/add_league', [LeagueController::class, 'saveLeagueAsadmin']);
        Route::post('/admin/sent_invite', [LeagueController::class, 'sent_invite']);
        Route::post('/admin/sent_invite_league_admin', [LeagueController::class, 'sent_invite_league_admin']);
        Route::get('/admin/league_status/{id}/{status}', [LeagueController::class, 'league_status']);
        Route::get('/admin/league_admin_status/{id}/{status}', [LeagueController::class, 'league_admin_status']);
        Route::get('/admin/login-as-league-admin/{id}', [LeagueController::class, 'login_as_league_admin']);
        Route::get('/admin/edit_league/{id}', [LeagueController::class, 'addEditLeague']);
        Route::post('/admin/edit_league/{id}', [LeagueController::class, 'updateLeague']);
        //umpire
        Route::get('/admin/members', [UmpireController::class, 'view_umpires']);
        Route::get('/admin/delete-umpire/{id}', [UmpireController::class, 'delete_umpire']);
        Route::get('/admin/edit-umpire/{id}', [UmpireController::class, 'edit_umpire']);
        Route::post('/admin/edit-umpire/{id}', [UmpireController::class, 'save_umpire']);
        Route::get('/admin/login-as-umpire/{id}', [UmpireController::class, 'login_as_umpire']);
        Route::get('/admin/umpire_status/{id}/{status}', [UmpireController::class, 'umpire_status']);
        Route::post('/admin/assign_league/{id}', [UmpireController::class, 'assign_league']);
        Route::get('/admin/get_leaguelist/{id}', [UmpireController::class, 'get_leaguelist']);
        //settings
        Route::get('/admin/add_preset', [SettingsController::class, 'add_preset']);
        Route::get('/admin/delete_preset/{id}', [SettingsController::class, 'delete_preset']);
        Route::post('/admin/add_preset', [SettingsController::class, 'save_preset']);
        Route::post('/admin/update_preset/{id}', [SettingsController::class, 'update_preset']);
        //all preset view goes through this
        Route::get('/admin/point-preset/{type}', [SettingsController::class, 'view_point_settings_based_on_preset']);
        //save various point presets
        Route::post('/admin/save_base_point/{id}', [SettingsController::class, 'save_base_point']);
        Route::post('/admin/save_age_of_players/{id}', [SettingsController::class, 'save_age_of_players']);
        Route::post('/admin/save_location_preset/{id}', [SettingsController::class, 'save_location_preset']);
        Route::post('/admin/save_pay/{id}', [SettingsController::class, 'save_pay']);
        Route::get('/admin/toggle-feedback-option', [SettingsController::class, 'toggleFeedBackOption']);
        Route::post('/admin/save_time/{id}', [SettingsController::class, 'save_time']);
        Route::post('/admin/save_umpire_duration/{id}', [SettingsController::class, 'save_umpire_duration']);
        Route::post('/admin/save_total_game/{id}', [SettingsController::class, 'save_total_game']);
        Route::post('/admin/save_umpire_position/{id}', [SettingsController::class, 'save_umpire_position']);
        Route::post('/admin/save_day_of_week/{id}', [SettingsController::class, 'save_day_of_week']);
        //cms
        Route::get('/admin/subscription', [GeneralController::class, 'manage_subscription']);
        Route::post('/admin/subscription', [GeneralController::class, 'save_subscription']);
        Route::get('/admin/faq', [GeneralController::class, 'manage_faq']);
        Route::get('/admin/delete-faq/{id}', [GeneralController::class, 'delete_faq']);
        Route::post('/admin/faq', [GeneralController::class, 'save_faq']);
        Route::post('/admin/order-faq', [GeneralController::class, 'dragDrop']);
    });
});
