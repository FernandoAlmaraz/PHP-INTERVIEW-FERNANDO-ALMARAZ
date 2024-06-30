<?php

namespace App\reader;

use App\Models\Property;
use Exception;

class CsvImport
{
    protected $filePath;
    protected $columnNames = [
        'Latitud', 'Longitud',
        'ID', 'Titulo', 'Anunciante', 'Descripcion',
        'Reformado', 'Telefonos', 'Tipo', 'Precio', 'Precio por metro', 'Direccion', 'Provincia',
        'Ciudad', 'Metros cuadrados', 'Habitaciones', 'Baños', 'Parking',
        'Segunda mano', 'Armarios Empotrados', 'Construido en', 'Amueblado',
        'Calefacción individual', 'Certificación energética', 'Planta',
        'Exterior', 'Interior', 'Ascensor', 'Fecha', 'Calle', 'Barrio',
        'Distrito', 'Terraza', 'Trastero', 'Cocina Equipada', 'Cocina equipada', 'Aire acondicionado',
        'Piscina', 'Jardín', 'Metros cuadrados útiles', 'Apto para personas con movilidad reducida',
        'Plantas', 'Se admiten mascotas', 'Balcón'
    ];

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function import()
    {
        $file = $this->openFile();

        // Leer la primera línea como encabezados
        $headers = fgetcsv($file, 0, ';');

        $insertedCount = 0;
        $updatedCount = 0;

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // Construir un array asociativo para cada fila
            $rowData = $this->buildRowData($headers, $row);

            // Insertar o actualizar en la base de datos
            try {
                $result = $this->insertOrUpdateProperty($rowData);
                if ($result === 'inserted') {
                    $insertedCount++;
                } elseif ($result === 'updated') {
                    $updatedCount++;
                }
            } catch (Exception $e) {
                // Loggear el error o manejar según sea necesario
                echo "Error al insertar o actualizar: " . $e->getMessage() . "\n";
                continue; // Continuar con la siguiente fila en caso de error
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

            // Validar y convertir tipos de datos específicos
            switch ($columnName) {
                case 'Planta':
                case 'Habitaciones':
                case 'Baños':
                case 'Parking':
                case 'Certificación energética':
                case 'Metros cuadrados':
                case 'Metros cuadrados útiles':
                case 'Plantas':
                case 'Precio':
                case 'Precio por metro':
                    // Convertir a entero si es numérico
                    if (is_numeric($value)) {
                        $value = (int) $value;
                    } else {
                        $value = null; // Puedes manejar aquí casos de valores no válidos como 'NaN'
                    }
                    break;
                case 'Fecha':
                    // Convertir formato de fecha 'dd/mm/yyyy' a 'yyyy-mm-dd'
                    if ($this->isDate($value)) {
                        $value = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
                    } else {
                        $value = null; // Puedes manejar aquí casos de fechas no válidas
                    }
                    break;
                case 'Reformado':
                case 'Segunda mano':
                case 'Armarios Empotrados':
                case 'Amueblado':
                case 'Calefacción individual':
                case 'Exterior':
                case 'Interior':
                case 'Ascensor':
                case 'Terraza':
                case 'Trastero':
                case 'Cocina Equipada':
                case 'Cocina equipada':
                case 'Aire acondicionado':
                case 'Piscina':
                case 'Jardín':
                case 'Apto para personas con movilidad reducida':
                case 'Se admiten mascotas':
                case 'Balcón':
                    // Convertir a booleano si es una cadena representando 'true' o 'false'
                    $lowercaseValue = strtolower($value);
                    if ($lowercaseValue === 'true' || $lowercaseValue === 'false') {
                        $value = ($lowercaseValue === 'true');
                    } else {
                        $value = null; // Puedes manejar aquí casos de valores no válidos
                    }
                    break;
                default:
                    // Para otras columnas, conservar el valor tal cual
                    break;
            }

            // Manejo de valores vacíos para permitir nulos si es necesario
            if ($value === '') {
                $value = null; // O ajusta según lo que permita tu base de datos
            }

            $rowData[$columnName] = $value;
        }

        return $rowData;
    }




    protected function isDate($string)
    {
        // Función auxiliar para verificar si una cadena es una fecha válida en formato 'dd/mm/yyyy'
        $date = date_create_from_format('d/m/Y', $string);
        return $date && $date->format('d/m/Y') === $string;
    }




    protected function insertOrUpdateProperty($data)
    {
        // Verificar si $data está vacío o no tiene la clave 'ID'
        if (empty($data) || !isset($data['ID'])) {
            throw new Exception('Los datos no son válidos para la inserción.');
        }

        // Ejemplo de inserción masiva utilizando el modelo Property
        Property::updateOrCreate(
            ['ID' => $data['ID']], // Buscar por la columna ID
            $data // Datos a insertar o actualizar
        );
    }

    protected function displayRowData($rowData)
    {
        echo "Datos de la fila:\n";
        foreach ($rowData as $columnName => $value) {
            echo "$columnName: $value\n";
        }
        echo "\n";
    }
}
