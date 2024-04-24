<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
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
