<?php

namespace App\HelpTools;

use App\Models\Property;
use Illuminate\Support\Facades\DB;

class AveragePriceCalculator
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function calculateAverage()
    {
        $latitude = $this->formatCoordinate($this->request->latitude);
        $longitude = $this->formatCoordinate($this->request->longitude);
        $distanceKm = $this->request->query('distancekm');
        $distanceMeters = $distanceKm * 1000;

        $averagePrice = Property::select(DB::raw('AVG(Precio) as averagePrice'))
            ->whereRaw("ST_Distance_Sphere(ST_GeomFromText('POINT($longitude $latitude)'), ST_GeomFromText('POINT($longitude $latitude)')) <= ?", [$distanceMeters])
            ->value('averagePrice');

        return $averagePrice;
    }
    private function formatCoordinate($coordinate)
    {
        $coordinate = str_replace('.', '', $coordinate);
        $coordinate = substr_replace($coordinate, '.', -6, 0);
        return $coordinate;
    }
}
