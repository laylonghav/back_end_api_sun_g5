<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model; // For SQL
use MongoDB\Laravel\Eloquent\Model; //for MongoDB

class Order extends Model
{
    protected $collection = "mongodb";
    protected $table = "orders";
    protected $fillable = ["order_no", "paid_amount", "total_amount", "remark", "paymemt_method"];


    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, "order_id", "_id");
    }
}
