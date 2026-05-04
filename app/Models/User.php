<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Distributeur;
use App\Models\Veterinaire;
use App\Models\Eleveur;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'address',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔗 علاقة مع distributeur
    public function distributeur()
    {
        return $this->hasOne(Distributeur::class);
    }

    // 🔗 علاقة مع vétérinaire
    public function veterinaire()
    {
        return $this->hasOne(Veterinaire::class);
    }

    // 🔗 علاقة مع éleveur
    public function eleveur()
    {
        return $this->hasOne(Eleveur::class);
    }
    /**
 * الرسائل التي أرسلها المستخدم
 */
public function sentMessages()
{
    return $this->hasMany(Message::class, 'sender_id');
}

/**
 * الرسائل التي استلمها المستخدم
 */
public function receivedMessages()
{
    return $this->hasMany(Message::class, 'receiver_id');
}
}