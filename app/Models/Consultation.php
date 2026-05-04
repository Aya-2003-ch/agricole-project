<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;

   protected $fillable = [
    'eleveur_id',
    'veterinaire_id',
    'date_demande',
    'date_consultation',
    'motif',
    'degree',
    'status',
    'diagnostique'
];

// العلاقات
public function veterinaire()
{
    return $this->belongsTo(User::class, 'veterinaire_id');
}

public function eleveur()
{
    return $this->belongsTo(User::class, 'eleveur_id');
}

}
