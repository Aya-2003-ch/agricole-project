<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    use softDeletes;
    
    protected $fillable =[
        'nom',
         'lib'
    ];

    public function store()
{
    return $this->belongsToMany(store::class, 'store')
                ->withPivot('quantite', 'date_exp', 'prix')
                ->withTimestamps();
}
}
