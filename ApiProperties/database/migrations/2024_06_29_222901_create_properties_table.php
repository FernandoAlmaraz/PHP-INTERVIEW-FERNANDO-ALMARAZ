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
            $table->string('Latitud')->nullable();
            $table->string('Longitud')->nullable();
            $table->integer('ID')->nullable();
            $table->string('Titulo')->nullable();
            $table->string('Anunciante')->nullable();
            $table->text('Descripcion')->nullable();
            $table->boolean('Reformado')->nullable();
            $table->string('Telefonos')->nullable();
            $table->string('Tipo')->nullable();
            $table->decimal('Precio', 10, 2)->nullable();
            $table->decimal('Precio por metro', 10, 2)->nullable();
            $table->string('Direccion')->nullable();
            $table->string('Provincia')->nullable();
            $table->string('Ciudad')->nullable();
            $table->integer('Metros cuadrados')->nullable();
            $table->integer('Habitaciones')->nullable();
            $table->integer('Baños')->nullable();
            $table->integer('Parking')->nullable();
            $table->boolean('Segunda mano')->nullable();
            $table->boolean('Armarios Empotrados')->nullable();
            $table->integer('Construido en')->nullable();
            $table->boolean('Amueblado')->nullable();
            $table->boolean('Calefaccion individual')->nullable();
            $table->string('Certificacion energetica')->nullable();
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
            $table->boolean('Cocina Equipada')->nullable();
            $table->boolean('Aire acondicionado')->nullable();
            $table->boolean('Piscina')->nullable();
            $table->boolean('Jardin')->nullable();
            $table->integer('Metros cuadrados utiles')->nullable();
            $table->boolean('Apto para personas con movilidad reducida')->nullable();
            $table->integer('Plantas')->nullable();
            $table->boolean('Se admiten mascotas')->nullable();
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
