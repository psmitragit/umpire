<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulePresetModel extends Model
{
    use HasFactory;
    protected $table = 'ucpreset_schedule';
    protected $primaryKey = "id";
    protected $fillable = ['presetid', 'addless', 'point'];
    public function preset(){
        return $this->belongsTo(PresetModel::class, 'presetid');
    }
}
