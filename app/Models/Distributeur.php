<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributeur extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id','nom', 'telephone', 'address','latitude',  
    'longitude'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'store')
            ->withPivot('quantite', 'date_exp')
            ->withTimestamps();
    }
    public function stores()
     {
        return $this->hasMany(Store::class);
       }  
       public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}
}
