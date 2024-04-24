<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueEmailSettingsModel extends Model
{
    use HasFactory;
    protected $table = 'leagueemailsettings';
    protected $primaryKey = "id";
    protected $fillable = ['leagueid', 'leave_game', 'join_game', 'apply'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
