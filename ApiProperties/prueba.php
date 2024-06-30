<?php

$filePath = 'C:/Users/Ferchex/Desktop/PHP-INTERVIEW-FERNANDO-ALMARAZ/ApiProperties/resource_accommodation.csv';

$file = fopen($filePath, 'r');

if (!$file) {
    die('No se pudo abrir el archivo');
}

// Leer el archivo línea por línea
while (($row = fgetcsv($file)) !== false) {
    // Procesar cada línea del archivo CSV
    var_dump($row); // Por ejemplo, aquí puedes imprimir o procesar cada fila
}

fclose($file); // Cerrar el archivo al finalizar