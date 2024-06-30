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
        'Precio_por_metro',
        'Direccion',
        'Provincia',
        'Ciudad',
        'Metros_cuadrados',
        'Habitaciones',
        'Baños',
        'Parking',
        'Segunda_mano',
        'Armarios_Empotrados',
        'Construido_en',
        'Amueblado',
        'Calefaccion_individual',
        'Certificacion_energetica',
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
        'Metros_cuadrados_utiles',
        'Apto_para_personas_con_movilidad_reducida',
        'Plantas',
        'Se_admiten_mascotas',
        'Balcon'
    ];
}
