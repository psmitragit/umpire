<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameReportModel extends Model
{
    use HasFactory;
    protected $table = 'gamereports';
    protected $primaryKey = "grid";
    protected $fillable = ['gameid', 'umpid', 'rqid', 'answer'];
    public function game(){
        return $this->belongsTo(GameModel::class, 'gameid');
    }
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function question(){
        return $this->belongsTo(ReportModel::class, 'rqid');
    }
}
