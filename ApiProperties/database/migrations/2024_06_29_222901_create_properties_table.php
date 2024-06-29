<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('Latitud')->nullable();
            $table->string('Longitud')->nullable();
            $table->string('Titulo')->nullable();
            $table->string('Anunciante')->nullable();
            $table->text('Descripcion')->nullable();
            $table->boolean('Reformado')->nullable();
            $table->string('Telefonos')->nullable();
            $table->string('Tipo')->nullable();
            $table->decimal('Precio', 10, 2)->nullable();
            $table->decimal('Precio_por_metro', 10, 2)->nullable();
            $table->string('Direccion')->nullable();
            $table->string('Provincia')->nullable();
            $table->string('Ciudad')->nullable();
            $table->integer('Metros_cuadrados')->nullable();
            $table->integer('Habitaciones')->nullable();
            $table->integer('Baños')->nullable();
            $table->integer('Parking')->nullable();
            $table->boolean('Segunda_mano')->nullable();
            $table->boolean('Armarios_Empotrados')->nullable();
            $table->integer('Construido_en')->nullable();
            $table->boolean('Amueblado')->nullable();
            $table->boolean('Calefaccion_individual')->nullable();
            $table->string('Certificacion_energetica')->nullable();
            $table->integer('Planta')->nullable();
            $table->boolean('Exterior')->nullable();
            $table->boolean('Interior')->nullable();
            $table->boolean('Ascensor')->nullable();
            $table->dateTime('Fecha')->nullable();
            $table->string('Calle')->nullable();
            $table->string('Barrio')->nullable();
            $table->string('Distrito')->nullable();
            $table->boolean('Terraza')->nullable();
            $table->boolean('Trastero')->nullable();
            $table->boolean('Cocina_Equipada')->nullable();
            $table->boolean('Aire_acondicionado')->nullable();
            $table->boolean('Piscina')->nullable();
            $table->boolean('Jardin')->nullable();
            $table->integer('Metros_cuadrados_utiles')->nullable();
            $table->boolean('Apto_para_personas_con_movilidad_reducida')->nullable();
            $table->integer('Plantas')->nullable();
            $table->boolean('Se_admiten_mascotas')->nullable();
            $table->boolean('Balcon')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
