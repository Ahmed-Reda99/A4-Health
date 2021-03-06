<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
class Patient extends Model
{
    use HasFactory,HasApiTokens,Notifiable;
    public $timestamps = false;
    protected $fillable = [
        'id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'id');
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
