<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyToLeague extends Model
{
    use HasFactory;

    protected $table = 'apply_to_league';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'status'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
}
