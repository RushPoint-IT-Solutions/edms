<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlCode extends Model
{
    protected $fillable = ['code', 'description', 'status'];
}