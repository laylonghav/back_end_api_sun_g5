<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Order::with('orderDetail')->get();
        return $this->apiResponseSuccess($order, "Get order successfully.", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ["order_no", "paid_amount", "total_amount", "remark", "paymemt_method"];
        //  ["price", "qty", "discount", "total", "order_id", "product_id"];
        $validate = $request->validate([
            "paid_amount" => "required|string",
            "total_amount" => "required|string",
            "remark" => "nullable|string",
            "paymemt_method" => "required|string",
            "detail" => "required|array",
            "detial.*.price" => "required|string",
            "detial.*.qty" => "required|string",
            "detial.*.discount" => "required|string",
            "detial.*.total" => "required|string",
            "detial.*.product_id" => "required|exists:products,id",
        ]);

        $orderNo = null;

        //1. Generate order_no
        // ORD 0 0004
        // _id :[ORD00001,ORD00002,ORD00003,ORD00004]
        $lastOrderNo = Order::groupBy("id", "desc")->first();
        if ($lastOrderNo) {
            $lastNumber = (int) substr($lastOrderNo->order_no, 3); // 0004
            $orderNo = "ORD" . str_pad($lastNumber + 1, 5, "0", STR_PAD_LEFT); // ORD+ 0 + 0005
        } else {
            $orderNo = "ORD00001";
        }

        // ["order_no", "paid_amount", "total_amount", "remark", "paymemt_method"];
        //2. create order 
        $order = Order::create([
            "order_no" => $orderNo,
            "paid_amount" => $validate["paid_amount"],
            "total_amount" => $validate["total_amount"],
            "remark" => $validate["remark"],
            "paymemt_method" => $validate["paymemt_method"],
        ]);

        //  ["price", "qty", "discount", "total", "order_id", "product_id"];
        //3. Create order detail
        if ($order) {
            foreach ($validate["detail"] as $item) {
                OrderDetail::create([
                    "order_id" => $order->id,
                    "product_id" => $item["product_id"],
                    "price" => $item["price"],
                    "qty" => $item["qty"],
                    "discount" => $item["discount"],
                    "total" => $item["total"],
                ]);

                // Stock
                // current product
                $product = Product::find($item["product_id"]);
                $currentQty = $product->qty;
                $orderQty = $item["qty"];

                $newQty = max(0, $currentQty - $orderQty);
                $product->update(["qty" => $newQty]);
            }

            return $this->apiResponseSuccess($order->load("orderDetail"), "Created order successfully.", 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->apiNotFound("Order is not found.");
        }

        return $this->apiResponseSuccess($order->load("orderDetail"), "Get one order successfully.", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->apiNotFound("Order is not found.");
        }

        $order->update($request->only(["order_no", "paid_amount", "total_amount", "remark", "paymemt_method"]));
        return $this->apiResponseSuccess($order->load("orderDetail"), "Updated order successfully.", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->apiNotFound("Order is not found.");
        }

        // Delete order
        $order->delete();
        // Delete orderDetail
        $order->orderDetail()->delete();
        return $this->apiResponseSuccess($order->load("orderDetail"), "Deleted order successfully.", 200);
    }
}
