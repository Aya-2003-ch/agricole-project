<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    use SoftDeletes;

   protected $fillable = [
    'eleveur_id',
    'veterinaire_id',
    'date_demande',
    'date_consultation',
    'motif',
    'degree',
    'status',
    'diagnostique',
    'is_accepted_by_eleveur'
];

// العلاقات
public function veterinaire()
{
    return $this->belongsTo(User::class, 'veterinaire_id');
}

public function eleveur()
{
    return $this->belongsTo(User::class, 'eleveur_id');
}
public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function animal()
    {
    return $this->belongsTo(Animal::class, 'animal_id');
    }

}
