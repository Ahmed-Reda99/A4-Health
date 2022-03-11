<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'invoiceID',
        'patient_id',
        'reservation_id'
    ];
    protected $primaryKey = 'invoiceID';
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    
}
