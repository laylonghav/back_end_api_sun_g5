<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ProductController extends Controller
{
    // =========== CRUD Operation ===============

    // Read data 
    public function index()
    {
        $product = Product::with(["productDetail", 'category', "brand"])->get();

        return response()->json([
            "data" => $product,
            "message" => "Get products successfully."
        ], 200);
    }

    // Store new data  = create
    public function store(Request $req)
    {
        // $inputData = [
        //     "name" => $req->input("name"),
        //     "description" => $req->input("description"),
        //     "price" => $req->input("price"),
        //     "qty" => $req->input("qty"),
        //     "discount" => $req->input("discount"),
        //     "status" => $req->input("status"),
        // ];

        // Validation
        $validate = $req->validate([
            "name" => "required|string|min:3|max:255|unique:products,name",
            "description" => "nullable|string|min:3|max:255",
            "price" => "required|integer",
            "qty" => "required|integer|min:1",
            "discount" => "required|integer|min:1",
            "status" => "required|boolean",
            "category_id" => "required|exists:categories,id",
            "brand_id" => "required|exists:brands,id",
            "image" => "nullable|image|mimes:jpeg,jpg,png,gif|max:2048", // 2M = 2048 (1M= 1024)
        ]);

        // // Handle image upload
        // if ($req->hasFile("image")) {
        //     $validate["image"] = $req->file("image")->store("products", "public");
        // }

        if ($req->hasFile('image')) {
            $uploaded = Cloudinary::uploadApi()->upload(
                $validate['image']->getRealPath(),
                ['folder' => config('cloudinary.upload_folder', 'fullstack_sun_g5')]
            );
            $validate['image_url'] = $uploaded['secure_url'];
            $validate['image_public_id'] = $uploaded['public_id'];
        }


        // Create new product into DB
        $product = new Product();

        // $product->fill($inputData);
        $product->fill($validate);
        $product->save();

        return response()->json([
            "data" => $product->load(["productDetail", "category", "brand"]),
            "message" => "Created product successfully."
        ], 201);
    }

    // Get one 
    public function get($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "message" => "Product is not found"
            ], 404);
        }

        return response()->json([
            "data" => $product->load(["productDetail", 'category', "brand"]),
            "message" => "Get one products successfully."
        ], 200);
    }

    // Update data 
    public function update(Request $req, $id)
    {
        // Find product for update 
        $product = Product::find($id);
        if (!$product) {
            return [
                "message" => "Product is not found"
            ];
        }

        // New data for update product
        // $inputData = [
        //     "name" => $req->input("name"),
        //     "description" => $req->input("description"),
        //     "price" => $req->input("price"),
        //     "qty" => $req->input("qty"),
        //     "discount" => $req->input("discount"),
        //     "status" => $req->input("status"),
        // ];

        $validate = $req->validate([
            "name" => "required|string|min:3|max:255|unique:products,name," . $product->id,
            "description" => "nullable|string|min:3|max:255",
            "price" => "required|integer",
            "qty" => "required|integer|min:1",
            "discount" => "required|integer|min:1",
            "status" => "required|boolean",
            "category_id" => "required|exists:categories,id",
            "brand_id" => "required|exists:brands,id",
            "image" => "nullable|image|mimes:jpeg,jpg,png,gif|max:2048", // 2M = 2048 (1M= 1024)
        ]);

        // Handle image upload
        // if ($req->hasFile("image")) {
        //     // Delete old image
        //     if ($product->image && Storage::disk("public")->exists($product->image)) {
        //         Storage::disk("public")->delete($product->image);
        //     }

        //     // New image
        //     $validate["image"] = $req->file("image")->store("products", "public");
        // }

        if ($req->hasFile('image')) {    // Delete old image
            if (!empty($product->image_public_id)) {
                Cloudinary::uploadApi()->destroy($product->image_public_id);
            }
            // Upload new image
            $uploaded = Cloudinary::uploadApi()->upload(
                $req->file('image')->getRealPath(),
                ['folder' => config('cloudinary.upload_folder', 'fullstack_sun_g5')]
            );
            $validate['image_url'] = $uploaded['secure_url'];
            $validate['image_public_id'] = $uploaded['public_id'];
        }


        // Update new product into DB
        // $product->fill($inputData);
        $product->fill($validate);
        $product->save();

        return response()->json([
            "data" => $product->load(["productDetail", 'category', "brand"]),
            "message" => "Updated product successfully."
        ], 200);
    }

    // Remove data = delete
    public function destroy($id)
    {

        // Find product for delete 
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "message" => "Product is not found"
            ], 404);
        }


        // // Delete image
        // if ($product->image && Storage::disk("public")->exists($product->image)) {
        //     Storage::disk("public")->delete($product->image);
        // }

        // Delete image from Cloudinary if it exists
        if (!empty($product->image_public_id)) {
            Cloudinary::uploadApi()->destroy($product->image_public_id);
        }


        // Delete product
        $product->delete();

        return response()->json([
            "id" => $id,
            "data" => $product->load(["productDetail", 'category', "brand"]),
            "message" => "Delected product successfully."
        ], 200);
    }

}
