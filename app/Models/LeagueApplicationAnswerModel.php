<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueApplicationAnswerModel extends Model
{
    use HasFactory;
    protected $table = 'leagueapplications';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'lqid', 'answer'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function league_question(){
        return $this->belongsTo(LeagueApplicationModel::class, 'lqid');
    }
}
