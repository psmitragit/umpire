<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockDivisionModel extends Model
{
    use HasFactory;
    protected $table = 'blockdivision';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'divid'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function division(){
        return $this->belongsTo(TeamDivisionModel::class, 'divid');
    }
}
