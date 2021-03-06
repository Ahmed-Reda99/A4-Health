<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'appointment_id',
        'patient_time',
        'patient_id',
        'status',
        'payment_status'
    ];
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

}
