<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Models\Brand;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use ApiResponseTrait;
    // Search brand 
    public function search(Request $req)
    {
        $query = $req->query("query");
        $brand = Brand::where("name", "like", "%" . $query . "%")->get();

        return response()->json([
            "Query" => $query,
            "data" => $brand,
            "message" => "Search brand successfully."
        ], 200);

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Brand::with(["product"])->get();

        // return response()->json([
        //     "data" => $category,
        //     "message" => "Get brands successfully."
        // ], 200);
        return $this->apiResponseSuccess($category, "Get brands successfully.", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        // $validate = $request->validate([
        //     "name" => "required|string|max:255|min:3|unique:categories,name",
        //     "description" => "nullable|string|min:3|max:2555"
        // ]);

        $validate = $request->validated();

        // Create new category
        // $category = Brand::create($validate);
        $category = Brand::create($validate);

        return response()->json([
            "data" => $category->load(["product"]),
            "message" => "Created brand successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Brand::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Brand is not found"
            ], 404);
        }

        return response()->json([
            "data" => $category->load(["product"]),
            "message" => "Get one brand successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find category
        $category = Brand::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Brand is not found"
            ], 404);
        }

        // New data for update
        $validate = $request->validate([
            "name" => "required|string|max:255|min:3|unique:categories,name," . $category->id,
            "description" => "nullable|string|min:3|max:2555"
        ]);

        // Update category
        $category->update($validate);

        return response()->json([
            "data" => $category->load(["product"]),
            "message" => "Updated brand successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find category
        $category = Brand::find($id);
        if (!$category) {
            return response()->json([
                "message" => "brand is not found"
            ], 404);
        }

        // Deleate category
        $category->delete();

        return response()->json([
            "id" => $id,
            "data" => $category->load(["product"]),
            "message" => "Deleted brand successfully."
        ], 200);
    }
}
