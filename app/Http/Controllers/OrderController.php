<?php

namespace App\Http\Controllers;

use App\Events\OrderAdded;
use App\Events\OrderConfirm;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $products = collect($request->input('products', []))->map(
            fn($item) => array_merge(
                $item,
                Product::select('price')->find($item['product_id'])->toArray()
            )
        );

        /** @var User $user */
        $user = $request->user();

        $total_price = $products->sum(fn($item) => $item['price'] * $item['quantity']);

        /** @var Order $order */
        $order = $user->customer->orders()->create([
            'store_id' => $request->input('store_id'),
            'total_price' => $total_price,
        ]);

        $order->products()->attach(
            $products->mapWithKeys(
                fn($item) => [
                    $item['product_id'] => [
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity']
                    ]
                ]
            )
        );

        // event(new OrderAdded($order));
        return $order;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        /** @var User $user */
        $user = $request->user();
        return $user->store->orders()->with(['customer.user', 'products'])->findOrFail($id);
    }


    public function confirm(Request $request, string $id)
    {
        $order = Order::find($id);
        event(new OrderConfirm($order));
    }
}
