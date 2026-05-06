<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commande extends Model
{
    use SoftDeletes;

   protected $fillable = [
    'sender_id', 'receiver_id', 'product_id', 'quantity', 'telephone', 'address', 'status', 'order_type'
];

    // المستخدم (acheteur)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // livreur
    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'livreur_id');
    }

    // ventes
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_commande');
    }
}