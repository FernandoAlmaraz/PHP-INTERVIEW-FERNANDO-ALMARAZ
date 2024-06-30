<?php

namespace App\reader;

use App\Models\Property;
use Exception;

class CsvImport
{
    protected $filePath;
    protected $columnNames = [
        'ID', 'Latitud', 'Longitud', 'Titulo', 'Anunciante', 'Descripcion',
        'Reformado', 'Telefonos', 'Tipo', 'Precio', 'Direccion', 'Provincia',
        'Ciudad', 'Metros_cuadrados', 'Habitaciones', 'Baños', 'Parking',
        'Segunda_mano', 'Armarios_Empotrados', 'Construido_en', 'Amueblado',
        'Calefaccion_individual', 'Certificacion_energetica', 'Planta',
        'Exterior', 'Interior', 'Ascensor', 'Fecha', 'Calle', 'Barrio',
        'Distrito', 'Terraza', 'Trastero', 'Cocina_Equipada', 'Aire_acondicionado',
        'Piscina', 'Jardin', 'Metros_cuadrados_utiles', 'Apto_para_personas_con_movilidad_reducida',
        'Plantas', 'Se_admiten_mascotas', 'Balcon'
    ];

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function import()
    {
        $file = $this->openFile();

        // Leer la primera línea como encabezados
        $headers = fgetcsv($file);

        $insertedCount = 0;
        $updatedCount = 0;

        while (($row = fgetcsv($file)) !== false) {
            // Construir un array asociativo para cada fila
            $rowData = $this->buildRowData($headers, $row);

            // Insertar o actualizar en la base de datos
            try {
                $this->insertOrUpdateProperty($rowData);
                $insertedCount++;
            } catch (Exception $e) {
                // Loggear el error o manejar según sea necesario
                continue;
            }
        }

        fclose($file);

        return [
            'inserted' => $insertedCount,
            'updated' => $updatedCount,
        ];
    }

    protected function openFile()
    {
        $file = fopen($this->filePath, 'r');

        if (!$file) {
            throw new Exception('No se pudo abrir el archivo.');
        }

        return $file;
    }

    protected function buildRowData($headers, $row)
    {
        $rowData = [];

        foreach ($this->columnNames as $index => $columnName) {
            $value = isset($row[$index]) ? $row[$index] : null;

            // Convertir valores numéricos a tipos adecuados (int, float, etc.)
            if (is_numeric($value)) {
                $value = $value + 0;
            }

            $rowData[$columnName] = $value;
        }

        return $rowData;
    }

    protected function insertOrUpdateProperty($data)
    {
        // Ejemplo de inserción masiva utilizando el modelo Property
        Property::updateOrCreate(
            ['ID' => $data['ID']], // Buscar por la columna ID
            $data // Datos a insertar o actualizar
        );
    }
}
