<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Models\Address;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class OrderController extends Controller
{
    public function purchase($id)
    {
        $user = auth()->user();
        $item = Item::find($id);
        $address = Address::where('user_id', $user->id)->first();
        return view('purchase', compact('item','user','address'));
    }

    public function createStripeSession(PurchaseRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();

        $item_id = $request->input('item_id');
        $existingOrder = Order::where('user_id', $user->id)->where('item_id', $item_id)->first();

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $amount = intval($request->input('amount'));

        $item_id = $request->input('item_id');
        if (empty($item_id)) {
            return response()->json(['error' => '商品IDが設定されていません。'], 400);
        }
        $payment_method = $request->input('payment_method');
        $session = Session::create([
            'payment_method_types' => [$payment_method],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $request->input('item_name'),
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('orders.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('orders.cancel'),
            'metadata' => [
                'item_id' => (string)$item_id,
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building_name' => $request->input('building_name'),
                'payment_method' => $request->input('payment_method'),
                'user_id' => auth()->user()->id,
            ],
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect('/')->with('error', 'セッションIDが見つかりません。');
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Stripeセッションの取得に失敗: ' . $e->getMessage());
        }
        $metadata = $session->metadata;
        $itemId = isset($metadata['item_id']) ? $metadata['item_id'] : null;
        $postal_code = isset($metadata['postal_code']) ? $metadata['postal_code'] : null;
        $address = isset($metadata['address']) ? $metadata['address'] : null;
        $building_name = isset($metadata['building_name']) ? $metadata['building_name'] : null;
        $payment_method = isset($metadata['payment_method']) ? $metadata['payment_method'] : null;

        if (!$itemId) {
            return redirect('/')->with('error', '商品IDが見つかりません。');
        }

        $item = Item::find($itemId);
        if (!$item || $item->status === 'sold') {
            return redirect('/')->with('error', 'この商品は既に販売済みです。');
        }
        $existingOrder = Order::where('user_id', auth()->id())->where('item_id', $item->id)->first();
        if ($existingOrder) {
            return redirect('/')->with('error', 'この商品は既に購入済みです。');
        }

        $order = new Order();
        $order->user_id = auth()->id();
        $order->item_id = $item->id;
        $order->postal_code = $postal_code;
        $order->address = $address;
        $order->building_name = $building_name;
        $order->payment_method = $payment_method;
        $order->save();

        $item->status = 'sold';
            $item->save();

        return view('list', ['items' => Item::all()]);
    }
}
