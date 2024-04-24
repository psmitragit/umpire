<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresetModel extends Model
{
    use HasFactory;
    protected $table = 'pointpresets';
    protected $primaryKey = "presetid";
    protected $fillable = ['presetid', 'presetname'];
    public function schedule() {
        return $this->hasMany(SchedulePresetModel::class, 'presetid');
    }
    public function age_of_players() {
        return $this->hasMany(Age_of_PlayersModel::class, 'presetid');
    }
    public function locations() {
        return $this->hasMany(GroundPresetModel::class, 'presetid');
    }
    public function pay() {
        return $this->hasMany(PayPresetModel::class, 'presetid');
    }
    public function time() {
        return $this->hasMany(TimePresetModel::class, 'presetid');
    }
    public function umpire_duration() {
        return $this->hasMany(UmpireDurationPresetModel::class, 'presetid');
    }
    public function total_game() {
        return $this->hasMany(TotalGamePresetModel::class, 'presetid');
    }
    public function umpire_position() {
        return $this->hasMany(UmpirePositionModel::class, 'presetid');
    }
    public function day_of_week() {
        return $this->hasMany(DayofWeekModel::class, 'presetid');
    }
}
