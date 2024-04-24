<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmpirePrefModel extends Model
{
    use HasFactory;
    protected $table = 'umpirepref';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'slno', 'leagueid'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
