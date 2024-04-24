<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutModel extends Model
{
    use HasFactory;
    protected $table = 'payouts';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'paydate', 'payamt', 'pmttype', 'gameid', 'owe', 'ump_pending'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function game(){
        return $this->belongsTo(GameModel::class, 'gameid');
    }
}
