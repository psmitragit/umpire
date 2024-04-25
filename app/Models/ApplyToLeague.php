<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplyToLeague extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'apply_to_league';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'status'];
    public function league()
    {
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function umpire()
    {
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
}
