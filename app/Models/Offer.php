<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'doc_id',
        'discount',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
