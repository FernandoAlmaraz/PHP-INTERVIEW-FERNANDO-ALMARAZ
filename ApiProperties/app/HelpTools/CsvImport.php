<?php

namespace App\HelpTools;

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

        $headers = fgetcsv($file, 0, ';');

        $insertedCount = 0;
        $updatedCount = 0;

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $rowData = $this->buildRowData($headers, $row);

            try {
                $result = $this->insertOrUpdateProperty($rowData);
                if ($result === 'inserted') {
                    $insertedCount++;
                } elseif ($result === 'updated') {
                    $updatedCount++;
                }
            } catch (Exception $e) {
                echo "Error al insertar o actualizar: " . $e->getMessage() . "\n";
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
                    if (is_numeric($value)) {
                        $value = (int) $value;
                    } else {
                        $value = null;
                    }
                    break;
                case 'Fecha':
                    if ($this->isDate($value)) {
                        $value = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
                    } else {
                        $value = null;
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

                    $lowercaseValue = strtolower($value);
                    if ($lowercaseValue === 'true' || $lowercaseValue === 'false') {
                        $value = ($lowercaseValue === 'true');
                    } else {
                        $value = null;
                    }
                    break;
                default:
                    break;
            }

            if ($value === '') {
                $value = null;
            }

            $rowData[$columnName] = $value;
        }

        return $rowData;
    }

    protected function isDate($string)
    {
        $date = date_create_from_format('d/m/Y', $string);
        return $date && $date->format('d/m/Y') === $string;
    }

    protected function insertOrUpdateProperty($data)
    {

        if (empty($data) || !isset($data['ID'])) {
            throw new Exception('Los datos no son válidos para la inserción.');
        }
        Property::updateOrCreate(
            ['ID' => $data['ID']],
            $data
        );
    }
}
