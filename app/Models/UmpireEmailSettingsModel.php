<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmpireEmailSettingsModel extends Model
{
    use HasFactory;
    protected $table = 'umpireemailsettings';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'schedule_game', 'cancel_game', 'payment', 'message', 'application'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
}
