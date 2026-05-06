<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commande extends Model
{
    use SoftDeletes;

   protected $fillable = [
    'sender_id', 'receiver_id', 'product_id', 'quantity', 'phone', 'address', 'status', 'order_type'
];

    // المستخدم (acheteur)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    
   public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'product_id');
    }

    // علاقة الطلب بالمشتري (المرسل)
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // علاقة الطلب بالبائع (المستقبل)
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // ventes
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_commande');
    }
}