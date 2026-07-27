<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'batiment', 
        'etage', 
        'capacite', 
        'est_ouverte', 
        'description'
    ];

    public function materiels()
    {
        return $this->hasMany(Materiel::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
