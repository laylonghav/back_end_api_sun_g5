<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model; // For SQL
use App\Trait\HashBarcodeTrait;
use MongoDB\Laravel\Eloquent\Model; //for MongoDB

class ProductDetail extends Model
{
    use HashBarcodeTrait;
    protected $connection = 'mongodb';
    protected $table = "product_details"; // table = collection
    protected $fillable = ["barcode", "color", "make_in", "product_id"];

    // Eloquent Relationship
    // One to One
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "_id");
    }
}
