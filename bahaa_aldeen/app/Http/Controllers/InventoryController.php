<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{


    public function index(): JsonResponse
    {
        $inventories = Inventory::with(['branch', 'models'])->get()->map(function ($inventory) {
            return [
                'id' => $inventory->id,
                'branch' => $inventory->branch,
                'total_models' => $inventory->models->count(),
                'rent_models' => $inventory->models->where('operation_type', 'rent')->count(),
                'sale_models' => $inventory->models->where('operation_type', 'sale')->count(),
                'created_at' => $inventory->created_at,
                'updated_at' => $inventory->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $inventories,
            'message' => 'تم جلب المخازن مع الإحصائيات بنجاح'
        ]);
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
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
