<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model; // For SQL
use MongoDB\Laravel\Eloquent\Model; //for MongoDB

class Brand extends Model
{

    protected $collection = "mongodb";
    protected $table = "brands";
    protected $fillable = ["name", "description"];

    // Relationship One-to-Many (Eloquent Relationship)
    public function product()
    {
        return $this->hasMany(Product::class, "brand_id", "_id");
    }
}
