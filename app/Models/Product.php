<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model; // For SQL
use MongoDB\Laravel\Eloquent\Model; //for MongoDB

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $table = "products"; // table = collection
    protected $fillable = ["name", "description", "price", "qty", "discount", 'status', "category_id", "brand_id", "image", "image_public_id", "image_url"];

    // protected $appends = ["imageurl"];

    // public function getImageUrlAttribute()
    // {
    //     if (!empty($this->image)) {
    //         return asset("storage/" . $this->image);
    //     }
    // }

    // Eloquent Relationship
    // One to One
    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class, "product_id", "_id");
    }

    // Relationship One-to-Many (Eloquent Relationship)
    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", "_id");
    }

    // Relationship One-to-Many (Eloquent Relationship)
    public function brand()
    {
        return $this->belongsTo(Brand::class, "brand_id", "_id");
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, "product_id", "_id");
    }
}
