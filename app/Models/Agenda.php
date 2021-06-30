<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Agenda extends Model
{
    use HasFactory, HasApiTokens;

    // Los atributos que soportan asignación en masa
    protected $fillable = [
        'evento_id',
        'date_agenda',
        'time_begin',
        'time_end',
        'title',
        'location',
        'description'
    ];

    // Obtener los speakers que hacen parte de esta agenda
    public function speakers()
    {
        return $this->belongsToMany(Speaker::class);
    }
}
