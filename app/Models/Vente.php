<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class vente extends Model
{
    use softDeletes;
    
    protected $fillable = [
        'qte',
        'prix',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store');
    }

}
