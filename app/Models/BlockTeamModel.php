<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockTeamModel extends Model
{
    use HasFactory;
    protected $table = 'blockteams';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'teamid'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function team(){
        return $this->belongsTo(TeamModel::class, 'teamid');
    }
}
