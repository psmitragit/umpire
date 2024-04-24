<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalGameLeagueModel extends Model
{
    use HasFactory;
    protected $table = 'lapreset_umpgames';
    protected $primaryKey = "id";
    protected $fillable = ['leagueid', 'addless', 'point', 'from', 'to'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
