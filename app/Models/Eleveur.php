<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eleveur extends Model
{
    use softDeletes;
    
    protected $fillable = [
        'user_id',
        'nom',
        'address',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'id_eleveur');
    }

}
