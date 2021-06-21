<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Evento extends Model
{
    use HasFactory, HasApiTokens;

    // La tabla asociada a este modelo
    protected $table = "eventos";

    // Los atributos que soportan asignación en masa
    protected $fillable = [
        'name',
        'description',
        'location',
        'user_id',
        'dateInit',
        'dateEnd'
    ];

    /**
     * La lista de contactos que participan en el evento
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    /**
     * La lista de conferencistas del evento
     */
    public function speakers()
    {
        return $this->belongsToMany(Speaker::class);
    }
}
