<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $primaryKey = "id";
    protected $fillable = ['leagueid', 'umpid', 'umpmsg', 'leaguemsg', 'type', 'iconid'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function icon(){
        return $this->belongsTo(Icon::class, 'iconid');
    }

}
