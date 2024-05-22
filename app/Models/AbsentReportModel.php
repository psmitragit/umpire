<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsentReportModel extends Model
{
    use HasFactory;
    protected $table = 'absent_report';
    protected $primaryKey = "id";
    protected $fillable = ['gameid', 'umpid', 'report_col'];
    public $timestamps = false;
}
