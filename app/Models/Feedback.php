<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'rate',
        'message',
    ];
    use HasFactory;
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
