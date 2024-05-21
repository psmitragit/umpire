<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighlightedReportModel extends Model
{
    use HasFactory;
    protected $table = 'highlighted_report';
    protected $primaryKey = "id";
    protected $fillable = ['gameid', 'report_col'];
    public $timestamps = false;
}
