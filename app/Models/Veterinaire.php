<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veterinaire extends Model
{
    use softDeletes;
    
    protected $fillable = [
        'user_id',
        'nom',
        'telephone',
        'address',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'id_veterinaire');
    }

}
