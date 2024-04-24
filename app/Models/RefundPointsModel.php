<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPointsModel extends Model
{
    use HasFactory;
    protected $table = 'refundpoints';
    protected $primaryKey = "id";
    protected $fillable = ['leagueumpires_id', 'game_id', 'addless', 'point'];
    public function leagueumpire(){
        return $this->belongsTo(LeagueUmpireModel::class, 'leagueumpires_id');
    }
    public function game(){
        return $this->belongsTo(GameModel::class, 'game_id');
    }
}
