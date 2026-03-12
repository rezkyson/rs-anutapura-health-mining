<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDataset extends Model
{
    protected $fillable = [
        'age',
        'gender',
        'blood_pressure',
        'cholesterol',
        'blood_sugar',
        'risk_result',
        'is_training',
    ];
}
