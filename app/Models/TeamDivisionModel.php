<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamDivisionModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'team_division';
    protected $primaryKey = "id";
    protected $fillable = ['name', 'leagueid'];
    public $timestamps = false;
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function teams(){
        return $this->hasMany(TeamModel::class, 'divid');
    }
    public function blockedDivisions(){
        return $this->hasMany(BlockDivisionModel::class, 'divid');
    }
}
