<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\HelpTools\CsvImport;
use App\HelpTools\AveragePriceCalculator;
use App\HelpTools\Filter;
use App\HelpTools\FilterByCoor;
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
        //
    }
    /**
     * Store a newly created resource in storage.
     * TASK 1
     * Test Body
     * {
     *      "pathFile":"H:/Users/myPC/Desk/netforemost/PHP-INTERVIEW-FERNANDO-ALMARAZ/ApiProperties/resource_accommodation.csv"
     *     }
     */
    public function store(StorePropertyRequest $request)
    {
        try {
            if (empty($request->pathFile) || !is_string($request->pathFile)) {
                throw new \Exception('El archivo a importar no es válido :/');
            }
            $importer = new CsvImport($request->pathFile);
            $importer->import();
            return response()->json(['message' => 'Datos importados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ha ocurrido un error al importar los datos. Por favor, inténtalo de nuevo más tarde.'], 500);
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
    /**
     * Filter by price and rooms.
     * TASK 2
     * Test Body
     * {
     *       "min_price":799,
     *       "max_price":10000,
     *       "rooms":9
     *       }
     */
    public function filterProperties(Request $request)
    {
        try {
            if (empty($request->all())) {
                throw new \Exception('El request está vacío o incompleto.');
            }
            $filter = new Filter($request);
            $filteredProperties = $filter->apply();
            return response()->json(['properties' => $filteredProperties], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ha ocurrido un error al filtrar las propiedades. Por favor, inténtalo de nuevo más tarde.'], 500);
        }
    }
    public function calculateAveragePrice(Request $request)
    {
        if (!$request->filled('latitude') || !$request->filled('longitude') || !$request->filled('distancekm')) {
            return response()->json(['error' => 'Los parámetros latitude, longitude y distancekm son obligatorios.'], 400);
        }
        try {
            $calculator = new AveragePriceCalculator($request);
            $averagePrice = $calculator->calculateAverage();

            return response()->json([
                'average_price_per_square_meter' => $averagePrice,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Los parámetros no son los esperados :(.'], 400);
        }
    }
    /**
     * Filter by price, rooms, report_type and generate report.
     * TASK 4
     * Test Body
     * {
     *       "min_price":799,
     *       "max_price":10000,
     *       "rooms":9
     *        "laitude":"403.628.468"
     *        "longitude":"-35.920.664"
     *         "report_type":"PDF"
     *       }
     */
    public function createReport(Request $request)
    {

        try {
            if (empty($request->all())) {
                throw new \Exception('El request está vacío o incompleto.');
            }
            $filter = new FilterByCoor($request);
            $filteredProperties = $filter->apply();

            if ($request->report_type === 'pdf') {
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
            return response()->json(['error' => 'Los parámetros no son los esperados :(.'], 400);
        }
    }
}
