<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmpirePositionModel extends Model
{
    use HasFactory;
    protected $table = 'ucpreset_umppos';
    protected $primaryKey = "id";
    protected $fillable = ['presetid', 'position', 'addless', 'point'];
    public function preset(){
        return $this->belongsTo(PresetModel::class, 'presetid');
    }
}
