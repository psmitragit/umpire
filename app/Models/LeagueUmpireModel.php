<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeagueUmpireModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'leagueumpires';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'points', 'payout', 'owed', 'received', 'bonus', 'due', 'status', 'notes'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function refundpoints(){
        return $this->hasMany(RefundPointsModel::class, 'leagueumpires_id');
    }
}
