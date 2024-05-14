<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameModel extends Model
{
    use HasFactory;
    protected $table = 'games';
    protected $primaryKey = "gameid";
    protected $fillable = ['leagueid', 'gamedate', 'gamedate_toDisplay', 'playersage', 'hometeamid', 'awayteamid', 'locid', 'umpreqd', 'ump1', 'ump2', 'ump3', 'ump4', 'report', 'report1', 'report2', 'report3', 'report4', 'ump1pay', 'ump1bonus', 'ump234pay', 'ump234bonus', 'manualAssignAlgoRunStatus'];
    public function league()
    {
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function hometeam()
    {
        return $this->belongsTo(TeamModel::class, 'hometeamid')->withTrashed();
    }
    public function awayteam()
    {
        return $this->belongsTo(TeamModel::class, 'awayteamid')->withTrashed();
    }
    public function location()
    {
        return $this->belongsTo(LocationModel::class, 'locid');
    }
    public function umpire1()
    {
        return $this->belongsTo(UmpireModel::class, 'ump1')->withTrashed();
    }
    public function umpire2()
    {
        return $this->belongsTo(UmpireModel::class, 'ump2')->withTrashed();
    }
    public function umpire3()
    {
        return $this->belongsTo(UmpireModel::class, 'ump3')->withTrashed();
    }
    public function umpire4()
    {
        return $this->belongsTo(UmpireModel::class, 'ump4')->withTrashed();
    }
    public function refundpoints(){
        return $this->hasMany(RefundPointsModel::class, 'game_id');
    }
}
