<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'teams';
    protected $primaryKey = "teamid";
    protected $fillable = ['teamname', 'leagueid', 'divid'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function division(){
        return $this->belongsTo(TeamDivisionModel::class, 'divid')->withTrashed();
    }
    public function blocked_umpire_teams(){
        return $this->hasMany(BlockTeamModel::class,'teamid');
    }
}
