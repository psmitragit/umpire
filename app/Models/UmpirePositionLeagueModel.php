<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmpirePositionLeagueModel extends Model
{
    use HasFactory;
    protected $table = 'lapreset_umppos';
    protected $primaryKey = "id";
    protected $fillable = ['leagueid', 'addless', 'point', 'position'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
