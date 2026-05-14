<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'identification_code',
        'eleveur_id',
    ];
    public function eleveur()
    {
        return $this->belongsTo(User::class, 'eleveur_id');
    }
    public function consultations()
    {
    return $this->hasMany(Consultation::class, 'animal_id');
    }
}
