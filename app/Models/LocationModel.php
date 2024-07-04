<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'locations';
    protected $primaryKey = "locid";
    protected $fillable = ['locid', 'ground', 'latitude', 'longitude', 'leagueid', 'address'];
    public function league()
    {
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
    public function blocked_umpire_grounds()
    {
        return $this->hasMany(BlockGroundModel::class, 'locid');
    }
}
