<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Speaker extends Model
{
    use HasFactory, HasApiTokens;

    // Los atributos que soportan asignación en masa
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'telephone',
        'image',
        'facebook',
        'linkedin',
        'instagram',
        'twitter'
    ];

    /**
     * La lista de eventos a los que pertenece este contacto
     */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class);
    }
}
