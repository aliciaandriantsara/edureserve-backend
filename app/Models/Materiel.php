<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;

    protected $fillable = [
        'salle_id', 
        'nom', 
        'quantite'
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
