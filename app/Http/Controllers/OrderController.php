<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::select(['id', 'name', 'description', 'date'])->with('products')->paginate(15);

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = Order::validate($request);
        $data['date'] = !empty($data['date']) ? Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d') : date('Y-m-d');

        $order = Order::create($data);
        if ($request->has('products')) {
            foreach ($request->products as $product) {
                $order->products()->attach($product['id'], ['quantity' => $product['quantity'] ?? 1]);
            }
        }

        return response()->json($order, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $data = Order::validate($request);
        $data['date'] = !empty($data['date']) ? Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d') : date('Y-m-d');

        $order->update($data);
        if ($request->has('products')) {
            $order->products()->detach();

            foreach ($request->products as $product) {
                $order->products()->attach($product['id'], ['quantity' => $product['quantity'] ?? 1]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->products()->detach();
        $order->delete();

        return response()->json(null, 204);
    }
}
