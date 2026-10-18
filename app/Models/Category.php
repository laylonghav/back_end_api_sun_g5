<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model; // For SQL
use MongoDB\Laravel\Eloquent\Model; //for MongoDB

class Category extends Model
{
    protected $collection = "mongodb";
    protected $table = "categories";
    protected $fillable = ["name", "status", "description"];

    // Relationship One-to-Many (Eloquent Relationship)
    public function product()
    {
        return $this->hasMany(Product::class, "category_id", "_id");
    }
}
