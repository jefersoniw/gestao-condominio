<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPeople extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'unitPeoples';
    protected $hidden = [
        'id_unit'
    ];
}
