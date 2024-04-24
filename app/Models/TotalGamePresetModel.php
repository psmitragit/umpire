<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalGamePresetModel extends Model
{
    use HasFactory;
    protected $table = 'ucpreset_umpgames';
    protected $primaryKey = "id";
    protected $fillable = ['presetid', 'from', 'to', 'addless', 'point'];
    public function preset(){
        return $this->belongsTo(PresetModel::class, 'presetid');
    }
}
