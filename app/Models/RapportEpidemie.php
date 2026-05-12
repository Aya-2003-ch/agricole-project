<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportEpidemie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_maladie',
        'localisation',
        'type_animal',
        'nombre_cas',
        'symptomes',
        'veterinaire_id',
    ];

    public function veterinaire()
    {
        return $this->belongsTo(User::class, 'veterinaire_id');
    }
}
