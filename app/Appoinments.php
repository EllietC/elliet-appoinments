<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appoinments extends Model
{
    protected $table='appoinments';
    protected $primaryKey = 'id';
    protected $fillable = array('date','email');
}
