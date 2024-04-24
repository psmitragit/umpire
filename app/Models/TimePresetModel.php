<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimePresetModel extends Model
{
    use HasFactory;
    protected $table = 'ucpreset_time';
    protected $primaryKey = "id";
    protected $fillable = ['presetid', 'from', 'to', 'addless', 'point'];
    public function preset(){
        return $this->belongsTo(PresetModel::class, 'presetid');
    }
}
