<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date_dammande',
        'date_consultation',
        'motif',
        'degree'
    ];

    public function veterinaire()
    {
        return $this->belongsTo(Veterinaire::class, 'id_veterinaire');
    }

    public function ferme()
    {
        return $this->belongsTo(Eleveur::class, 'id_eleveur');
    }

}
