<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundPresetModel extends Model
{
    use HasFactory;
    protected $table = 'ucpreset_ground';
    protected $primaryKey = "id";
    protected $fillable = ['presetid', 'locid', 'addless', 'point'];
    public function preset(){
        return $this->belongsTo(PresetModel::class, 'presetid');
    }
}
