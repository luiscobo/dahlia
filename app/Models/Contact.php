<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Contact extends Model
{
    use HasFactory, HasApiTokens;

    // Los atributos que soportan asignación en masa
    protected $fillable = [
        'lastName',
        'firstName',
        'email',
        'telephone'
    ];

    /**
     * La lista de eventos a los que pertenece este contacto
     */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class);
    }
}
