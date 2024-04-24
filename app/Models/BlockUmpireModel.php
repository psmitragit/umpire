<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockUmpireModel extends Model
{
    use HasFactory;
    protected $table = 'blockumpires';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
