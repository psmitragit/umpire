<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteMetaData extends Model
{
    use HasFactory;
    protected $table = 'site_meta_data';
    protected $primaryKey = "id";
    protected $fillable = ['meta_key', 'meta_value'];
    public $timestamps = false;
}
