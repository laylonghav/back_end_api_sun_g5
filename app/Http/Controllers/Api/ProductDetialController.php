<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductDetialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productDetail = ProductDetail::with(["product"])->get();

        return response()->json([
            "data" => $productDetail,
            "message" => "Get product detail successfully."
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validate = $request->validate([
            "barcode" => "nullable|string|min:3|max:255|unique:product_details,barcode",
            "color" => "required|string|min:3|max:255",
            "make_in" => "required|string|min:3|max:255",
            "product_id" => "required|exists:products,id",
        ]);

        


        // Create new product into DB
        $productDetail = new ProductDetail();

        // $productDetail->fill($inputData);
        $productDetail->fill($validate);
        $productDetail->save();

        return response()->json([
            "data" => $productDetail->load(["product"]),
            "message" => "Created product detail successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productDetail = ProductDetail::find($id);
        if (!$productDetail) {
            return response()->json([
                "message" => "Product detail is not found"
            ], 404);
        }

        return response()->json([
            "data" => $productDetail->load(["product"]),
            "message" => "Get one product detial successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $productDetail = ProductDetail::find($id);
        if (!$productDetail) {
            return response()->json([
                "message" => "Product detail is not found"
            ], 404);
        }

        // Validation
        $validate = $request->validate([
            "barcode" => "required|string|min:3|max:255|unique:product_details,barcode," . $productDetail->id,
            "color" => "required|string|min:3|max:255",
            "make_in" => "required|string|min:3|max:255",
            "product_id" => "required|exists:products,id",
        ]);

        // Update new product into DB
        // $productDetail->fill($inputData);
        $productDetail->fill($validate);
        $productDetail->save();

        return response()->json([
            "data" => $productDetail->load(["product"]),
            "message" => "Updated product detail successfully."
        ], 200);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productDetail = ProductDetail::find($id);
        if (!$productDetail) {
            return response()->json([
                "message" => "Product detail is not found"
            ], 404);
        }

        $productDetail->delete();

        return response()->json([
            "data" => $productDetail->load(["product"]),
            "message" => "Deleted product detail successfully."
        ], 200);
    }
}
