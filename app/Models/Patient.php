<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
