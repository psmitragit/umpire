<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueApplicationModel extends Model
{
    use HasFactory;
    protected $table = 'leaguequestions';
    protected $primaryKey = "lqid";
    protected $fillable = ['leagueid', 'question', 'order'];
    public function league(){
        return $this->belongsTo(LeagueModel::class, 'leagueid');
    }
}
