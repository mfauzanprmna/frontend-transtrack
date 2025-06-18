<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelDataset extends Model
{
    protected $table = 'model_datasets';

    protected $fillable = [
        'dataset_id',
        'model_name',
        'family_name',
    ];

    public function fileDataset()
    {
        return $this->belongsTo(file_dataset::class, 'dataset_id');
    }
}
