<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'produit_id',
        'distributeur_id',
        'quantite',
        'date_exp',
        'prix'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function distributeur()
    {
        return $this->belongsTo(Distributeur::class);
    }
}