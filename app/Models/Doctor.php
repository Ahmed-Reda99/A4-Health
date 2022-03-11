<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;


class Doctor extends Model
{
    use HasFactory,HasApiTokens,Notifiable;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'title',
        'description',
        'img_name',
        'street',
        'city',
        'specialization_id',
        'fees',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
