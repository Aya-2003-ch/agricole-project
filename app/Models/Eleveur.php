<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FermeAgricole extends Model
{
    use softDeletes;
    
    protected $fillable = [
        'nom',
        'localisation',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'id_eleveur');
    }

}
