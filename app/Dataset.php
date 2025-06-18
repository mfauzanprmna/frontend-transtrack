<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    protected $fillable = ['data', 'hash'];
    protected $casts = ['data' => 'array'];
}
