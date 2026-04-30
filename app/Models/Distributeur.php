<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributeur extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id','nom', 'tele', 'localisation'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'store')
            ->withPivot('quantite', 'date_exp')
            ->withTimestamps();
    }
}
