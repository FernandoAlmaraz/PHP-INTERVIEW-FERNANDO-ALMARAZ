<?php

namespace App\reader;

use App\Models\Property;
use Illuminate\Http\Request;

class Filter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply()
    {
        $minPrice = $this->request->query('min_price');
        $maxPrice = $this->request->query('max_price');
        $rooms = $this->request->query('rooms');

        $query = Property::query();

        if ($minPrice !== null && $maxPrice !== null) {
            $query->whereBetween('Precio', [(float) $minPrice, (float) $maxPrice]);
        } elseif ($minPrice !== null) {
            $query->where('Precio', '>=', (float) $minPrice);
        } elseif ($maxPrice !== null) {
            $query->where('Precio', '<=', (float) $maxPrice);
        }

        if ($rooms) {
            $query->where('Habitaciones', $rooms);
        }

        $properties = $query->paginate(10);

        return $properties;
    }
}
