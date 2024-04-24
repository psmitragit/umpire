<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayofWeekModel extends Model
{
    use HasFactory;
    protected $table = 'ucpreset_day';
    protected $primaryKey = "id";
    protected $fillable = ['presetid', 'dayname', 'addless', 'point'];
    public function preset(){
        return $this->belongsTo(PresetModel::class, 'presetid');
    }
}
