<?php

namespace App\HelpTools;

use App\Models\Property;

class AveragePriceCalculator
{
    protected $latitud;
    protected $longitud;
    protected $distanceKm;

    public function __construct($latitud, $longitud, $distanceKm)
    {
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->distanceKm = $distanceKm;
    }

    public function calculateAverage()
    {
        $property = Property::where('Latitud', $this->latitud)
            ->where('Longitud', $this->longitud)
            ->first(['Precio', 'Precio por metro', 'Metros cuadrados']);

        if ($property) {

            $price = $property->Precio;
            $pricePerMeter = $property->{'Precio por metro'};
            $squareMeter = $property->{'Metros cuadrados'};

            $kilometersSquare = $squareMeter / 1000000;

            $average = ($price + $pricePerMeter + $kilometersSquare) / 3;

            return $average;
        } else {
            return null;
        }
    }
}
