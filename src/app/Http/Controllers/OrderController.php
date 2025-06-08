<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|string',
            'address' => 'required|string',
            'building_name' => 'nullable|string',
            'payment_method' => 'required|string',
            'item_id' => 'required|integer',
        ]);

        $order = new Order();
        $order->user_id = auth()->id();
        $order->item_id = $request->input('item_id');
        $order->postal_code = $request->input('postal_code');
        $order->address = $request->input('address');
        $order->building_name = $request->input('building_name');
        $order->payment_method = $request->input('payment_method');

        $order->save();

        $item = Item::find($request->input('item_id'));
        if ($item) {
            $item->status = 'sold';
            $item->save();
        }

        return redirect('/')->with('success', 'Order completed!');
    }
}

