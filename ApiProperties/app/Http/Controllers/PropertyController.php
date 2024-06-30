<?php

namespace App\Http\Controllers;

use App\reader\CsvImport;
use App\reader\AveragePriceCalculator;
use App\reader\Filter;
use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::paginate();
        return response()->json(['properties' => $properties], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Creando datos'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyRequest $request)
    {
        try {
            $importer = new CsvImport($request->pathFile);
            $importer->import();

            return response()->json(['message' => 'Datos importados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        //
    }
    public function filterProperties(Request $request)
    {
        try {
            // Crear instancia del filtro y aplicar
            $filter = new Filter($request);
            $filteredProperties = $filter->apply();
            // Retornar respuesta JSON con propiedades filtradas
            return response()->json(['properties' => $filteredProperties], 200);
        } catch (\Exception $e) {
            // Manejar cualquier error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function calculateAveragePrice(Request $request)
    {
        $latitud = $request['latitud'];
        $longitud = $request['longitud'];
        $distanceKm = $request['distanceKm'];

        $calculator = new AveragePriceCalculator($latitud, $longitud, $distanceKm);

        $averagePrice = $calculator->calculateAverage();

        return response()->json([
            'average_price_per_square_meter' => $averagePrice,
        ], 200);
    }
}
