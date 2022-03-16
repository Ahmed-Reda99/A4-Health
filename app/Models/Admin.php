<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use HasFactory, HasApiTokens,Notifiable;
    public $timestamps = false;
    protected $primaryKey = "username";

    protected $fillable = [
        'username',
        'password'
    ];

    
}
