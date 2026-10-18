<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    // Search brand 
    public function search(Request $req)
    {
        $query = $req->query("query");
        $brand = Category::where("name", "like", "%" . $query . "%")
            ->orWhere("description", "like", "%" . $query . "%")->get();

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
        $category = Category::with(["product"])->get();

        // return response()->json([
        //     "data" => $category,
        //     "message" => "Get categorys successfully."
        // ], 200);
        return $this->apiResponseSuccess($category, "Get categorys successfully.", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "name" => "required|string|max:255|min:3|unique:categories,name",
            "description" => "nullable|string|min:3|max:2555",
            "status" => "required|boolean"
        ]);

        // Create new category
        $category = Category::create($validate);

        return response()->json([
            "data" => $category->load(["product"]),
            "message" => "Created categorys successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category is not found"
            ], 404);
        }

        return response()->json([
            "data" => $category->load(["product"]),
            "message" => "Get one category successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find category
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category is not found"
            ], 404);
        }

        // New data for update
        $validate = $request->validate([
            "name" => "required|string|max:255|min:3|unique:categories,name," . $category->id,
            "description" => "nullable|string|min:3|max:2555",
            "status" => "required|boolean"
        ]);

        // Update category
        $category->update($validate);

        return response()->json([
            "data" => $category->load(["product"]),
            "message" => "Updated categorys successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find category
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category is not found"
            ], 404);
        }

        // Deleate category
        $category->delete();

        return response()->json([
            "id" => $id,
            "data" => $category->load(["product"]),
            "message" => "Deleted categorys successfully."
        ], 200);
    }
}
