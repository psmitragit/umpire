<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleLeagueModel extends Model
{
    use HasFactory;
    protected $table = 'lapreset_schedule';
    protected $primaryKey = "id";
    protected $fillable = ['leagueid', 'addless', 'point'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
