<?php

namespace App\Http\Controllers;

use App\reader\CsvImport;
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
        $precioMin = $request->query('precio_min');
        $precioMax = $request->query('precio_max');
        $habitaciones = $request->query('habitaciones');
        $query = Property::query();

        if ($precioMin && $precioMax) {
            $query->whereBetween('Precio', [$precioMin, $precioMax]);
        } elseif ($precioMin) {
            $query->where('Precio', '>=', $precioMin);
        } elseif ($precioMax) {
            $query->where('Precio', '<=', $precioMax);
        }

        if ($habitaciones) {
            $query->where('Habitaciones', $habitaciones);
        }
        $properties = $query->paginate(10);

        return response()->json(['properties' => $properties], 200);
    }
}
