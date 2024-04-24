<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundLeagueModel extends Model
{
    use HasFactory;
    protected $table = 'lapreset_ground';
    protected $primaryKey = "id";
    protected $fillable = ['leagueid', 'addless', 'point', 'locid'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
