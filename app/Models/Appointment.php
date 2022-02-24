<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_time',
        'date',
        'patient_limit',
        'examination_time',
        'doc_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
}
