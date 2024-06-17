<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToggleSettings extends Model
{
    use HasFactory;
    protected $table = 'toggle_settings_off';
    protected $primaryKey = "id";
    protected $fillable = ['toggled_by', 'toggled_for', 'setting', 'point', 'from', 'to'];
}
