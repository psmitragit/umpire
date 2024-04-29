<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeagueModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'leagues';
    protected $primaryKey = "leagueid";
    protected $fillable = ['name', 'phone', 'leaguename', 'report', 'joiningpoint', 'assignbefore', 'leavebefore', 'defaultpay', 'mainumpage', 'otherumpage', 'status', 'umpire_joining_status', 'cc'];
    public function users()
    {
        return $this->hasMany(UserModel::class, 'leagueid', 'leagueid');
    }
    public function reports()
    {
        return $this->hasMany(ReportModel::class, 'leagueid')->orderBy('order');
    }
    public function notifications()
    {
        return $this->hasMany(NotificationModel::class, 'leagueid')->orderBy('id', 'DESC');
    }
    public function applications()
    {
        return $this->hasMany(LeagueApplicationModel::class, 'leagueid')->orderBy('order');
    }
    public function teams()
    {
        return $this->hasMany(TeamModel::class, 'leagueid');
    }
    public function divisions()
    {
        return $this->hasMany(TeamDivisionModel::class, 'leagueid');
    }
    public function location()
    {
        return $this->hasMany(LocationModel::class, 'leagueid');
    }
    public function schedule()
    {
        return $this->hasMany(ScheduleLeagueModel::class, 'leagueid');
    }
    public function age_of_players()
    {
        return $this->hasMany(Age_of_PlayersLeagueModel::class, 'leagueid');
    }
    public function locations()
    {
        return $this->hasMany(GroundLeagueModel::class, 'leagueid');
    }
    public function pay()
    {
        return $this->hasMany(PayLeagueModel::class, 'leagueid');
    }
    public function time()
    {
        return $this->hasMany(TimeLeagueModel::class, 'leagueid');
    }
    public function umpire_duration()
    {
        return $this->hasMany(UmpireDurationLeagueModel::class, 'leagueid');
    }
    public function total_game()
    {
        return $this->hasMany(TotalGameLeagueModel::class, 'leagueid');
    }
    public function umpire_position()
    {
        return $this->hasMany(UmpirePositionLeagueModel::class, 'leagueid');
    }
    public function day_of_week()
    {
        return $this->hasMany(DayofWeekLeagueModel::class, 'leagueid');
    }
    public function games()
    {
        return $this->hasMany(GameModel::class, 'leagueid')->where('status', 0);
    }
    public function umpires()
    {
        return $this->hasMany(LeagueUmpireModel::class, 'leagueid');
    }
    public function umpire_apply()
    {
        return $this->hasMany(ApplyToLeague::class, 'leagueid');
    }
    public function application_answers()
    {
        return $this->hasMany(LeagueApplicationAnswerModel::class, 'leagueid');
    }
    public function payouts()
    {
        return $this->hasMany(PayoutModel::class, 'leagueid');
    }
    public function blocked_umpires()
    {
        return $this->hasMany(BlockUmpireModel::class, 'leagueid');
    }
    public function blocked_umpire_teams()
    {
        return $this->hasMany(BlockTeamModel::class, 'leagueid');
    }
    public function blocked_umpire_grounds()
    {
        return $this->hasMany(BlockGroundModel::class, 'leagueid');
    }
    public function email_settings(){
        return $this->hasOne(LeagueEmailSettingsModel::class, 'leagueid');
    }
}
