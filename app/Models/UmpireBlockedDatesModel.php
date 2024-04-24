<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmpireBlockedDatesModel extends Model
{
    use HasFactory;
    protected $table = 'blockdates';
    protected $primaryKey = "id";
    protected $fillable = ['umpid', 'leagueid', 'blockdate', 'blocktime'];
    public function umpire(){
        return $this->belongsTo(UmpireModel::class, 'umpid');
    }
}
