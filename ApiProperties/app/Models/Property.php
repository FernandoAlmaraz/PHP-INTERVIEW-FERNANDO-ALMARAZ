<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'Latitud',
        'Longitud',
        'ID',
        'Titulo',
        'Anunciante',
        'Descripcion',
        'Reformado',
        'Telefonos',
        'Tipo',
        'Precio',
        'Precio por metro',
        'Direccion',
        'Provincia',
        'Ciudad',
        'Metros cuadrados',
        'Habitaciones',
        'Baños',
        'Parking',
        'Segunda mano',
        'Armarios Empotrados',
        'Construido en',
        'Amueblado',
        'Calefaccion individual',
        'Certificacion energetica',
        'Planta',
        'Exterior',
        'Interior',
        'Ascensor',
        'Fecha',
        'Calle',
        'Barrio',
        'Distrito',
        'Terraza',
        'Trastero',
        'Cocina_Equipada',
        'Aire_acondicionado',
        'Piscina',
        'Jardin',
        'Metros cuadrados utiles',
        'Apto para personas con movilidad reducida',
        'Plantas',
        'Se admiten mascotas',
        'Balcon'
    ];
}
