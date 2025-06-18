<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file_dataset extends Model
{
    protected $table = 'file_datasets';

    protected $fillable = [
        'file_name',
        'date_column',
        'sales_column',
        'family_column',
        'family_name',
        'store_column',
    ];

    public function getFileNameAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
