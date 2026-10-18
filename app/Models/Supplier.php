<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model; // For SQL
use MongoDB\Laravel\Eloquent\Model; //for MongoDB

class Supplier extends Model
{
    protected $collection = "mongodb";
    protected $table = "suppliers";
    protected $fillable = ["name", "email", "code", "website", "address"];
}
