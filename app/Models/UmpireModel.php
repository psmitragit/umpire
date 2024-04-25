<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UmpireModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'umpires';
    protected $primaryKey = "umpid";
    protected $fillable = ['umpid', 'name', 'phone', 'dob', 'zip', 'profilepic', 'bio', 'email_verify_status', 'status'];
    public function user(){
        return $this->belongsTo(UserModel::class, 'umpid');
    }
    public function leagues(){
        return $this->hasMany(LeagueUmpireModel::class, 'umpid');
    }
    public function applied_leagues(){
        return $this->hasMany(ApplyToLeague::class, 'umpid');
    }
    public function league_applications(){
        return $this->hasMany(LeagueApplicationAnswerModel::class, 'umpid');
    }
    public function blocked_dates(){
        return $this->hasMany(UmpireBlockedDatesModel::class, 'umpid');
    }
    public function email_settings(){
        return $this->hasOne(UmpireEmailSettingsModel::class, 'umpid');
    }
    public function payouts(){
        return $this->hasMany(PayoutModel::class, 'umpid');
    }
    public function blocked_leagues(){
        return $this->hasMany(BlockUmpireModel::class, 'umpid');
    }
    public function blocked_team(){
        return $this->hasMany(BlockTeamModel::class, 'umpid');
    }
    public function blocked_ground(){
        return $this->hasMany(BlockGroundModel::class, 'umpid');
    }
    public function pref(){
        return $this->hasMany(UmpirePrefModel::class, 'umpid');
    }
}
