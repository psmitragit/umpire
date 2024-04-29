<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamModel extends Model
{
    use HasFactory;
    protected $table = 'teams';
    protected $primaryKey = "teamid";
    protected $fillable = ['teamname', 'leagueid', 'divid'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function division(){
        return $this->belongsTo(TeamDivisionModel::class, 'divid');
    }
    public function blocked_umpire_teams(){
        return $this->hasMany(BlockTeamModel::class,'teamid');
    }
}
