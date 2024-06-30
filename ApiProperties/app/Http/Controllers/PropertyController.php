<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\HelpTools\CsvImport;
use App\HelpTools\AveragePriceCalculator;
use App\HelpTools\Filter;
use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $filter = new Filter($request);
            $filteredProperties = $filter->apply();
            return response()->json(['properties' => $filteredProperties], 200);
        } catch (\Exception $e) {
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
    public function createReport(Request $request)
    {
        try {
            $filter = new Filter($request);
            $filteredProperties = $filter->apply();

            if ($request->tipo_reporte === 'pdf') {
                $pdf = PDF::loadView('reporte.pdf', $filteredProperties);
                $content = $pdf->output();
                $fileName = 'reporte_' . uniqid() . '.pdf';
            } else {
                $content = $filteredProperties;
                $fileName = 'reporte_' . uniqid() . '.csv';
            }
            Storage::disk('public')->put('reportes/' . $fileName, $content);

            return response()->json([
                'mensaje' => 'Reporte generado correctamente',
                'archivo' => $fileName,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
