<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheep extends Model
{
    protected $fillable = ['corral', 'created_at', 'updated_at'];
}
