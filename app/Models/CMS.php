<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
    use HasFactory;
    protected $table = 'c_m_s';
    protected $primaryKey = "id";
    protected $fillable = ['page', 'section', 'value'];
}
