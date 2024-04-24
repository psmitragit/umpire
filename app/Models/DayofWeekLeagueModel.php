<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayofWeekLeagueModel extends Model
{
    use HasFactory;
    protected $table = 'lapreset_day';
    protected $primaryKey = "id";
    protected $fillable = ['leagueid', 'addless', 'point', 'dayname'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
