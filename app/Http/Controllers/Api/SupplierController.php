<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    // Search supplier 
    public function search(Request $req)
    {
        $query = $req->query("query");
        $supplier = Supplier::where("name", "like", "%" . $query . "%")
            ->orWhere("address", "like", "%" . $query . "%")->get();

        return response()->json([
            "Query" => $query,
            "data" => $supplier,
            "message" => "Search supplier successfully."
        ], 200);

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::query()->get();

        // return response()->json([
        //     "data" => $supplier,
        //     "message" => "Get suppliers successfully."
        // ], 200);
        return $this->apiResponseSuccess($supplier, "Get suppliers successfully.", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "name" => "required|string|max:255|min:3",
            "code" => "required|string|min:3|max:2555",
            "address" => "required|string|min:3|max:2555",
            "website" => "required|string|min:3|max:2555",
            "email" => "required|string|max:2555",
        ]);

        // Create new supplier
        $supplier = Supplier::create($validate);

        return response()->json([
            "data" => $supplier,
            "message" => "Created suppliers successfully."
        ], 201);
    }

    public function storeBluk(Request $request)
    {
        $validate = $request->validate([
            "supplier" => "required|array",
            "supplier.*.name" => "required|string|max:255|min:3",
            "supplier.*.code" => "required|string|min:3|max:2555",
            "supplier.*.address" => "required|string|min:3|max:2555",
            "supplier.*.website" => "required|string|min:3|max:2555",
            "supplier.*.email" => "required|string|max:2555",
        ]);

        // Create new supplier
        foreach ($validate['supplier'] as $item) {
            $supplier = Supplier::create($item);
        }

        return response()->json([
            "data" => $supplier,
            "message" => "Import suppliers successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json([
                "message" => "supplier is not found"
            ], 404);
        }

        return response()->json([
            "data" => $supplier,
            "message" => "Get one supplier successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find supplier
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json([
                "message" => "supplier is not found"
            ], 404);
        }

        // New data for update
        $validate = $request->validate([
            "name" => "required|string|max:255|min:3",
            "code" => "required|string|min:3|max:2555",
            "address" => "required|string|min:3|max:2555",
            "website" => "required|string|min:3|max:2555",
            "email" => "required|string|max:2555",
        ]);

        // Update supplier
        $supplier->update($validate);

        return response()->json([
            "data" => $supplier,
            "message" => "Updated suppliers successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find supplier
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json([
                "message" => "supplier is not found"
            ], 404);
        }

        // Deleate supplier
        $supplier->delete();

        return response()->json([
            "id" => $id,
            "data" => $supplier,
            "message" => "Deleted suppliers successfully."
        ], 200);
    }
}
