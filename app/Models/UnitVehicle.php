<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitVehicle extends Model
{
    use HasFactory;
    protected $table = 'unitVehicles';
    public $timestamps = false;
    protected $hidden = [
        'id_unit',
    ];
}
