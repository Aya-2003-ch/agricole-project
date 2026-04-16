<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class livreurs extends Model
{
    use softDeletes;
    
    protected $fillable = [
        'nom',
        'telephone',
        'adresse',
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'id_livreur');
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_livreur');
    }

}
