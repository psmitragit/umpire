<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'users';
    protected $primaryKey = "uid";
    protected $fillable = ['leagueid', 'email', 'password', 'otp', 'usertype', 'status'];
    public function league()
    {
        return $this->belongsTo(LeagueModel::class, 'leagueid', 'leagueid');
    }
    public function umpire()
    {
        return $this->hasOne(UmpireModel::class, 'umpid');
    }
}
