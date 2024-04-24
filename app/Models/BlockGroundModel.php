<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockGroundModel extends Model
{
    use HasFactory;
    protected $table = 'blocklocations';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'locid'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function ground(){
        return $this->belongsTo(LocationModel::class, 'locid');
    }
}
