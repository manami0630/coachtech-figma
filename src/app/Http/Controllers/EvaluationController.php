<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\EvaluationNotification;

class EvaluationController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $evaluatorId = auth()->id();
        $sellerId = $order->item->user_id;
        $buyerId = $order->user_id;

        $targetUserId = $evaluatorId === $sellerId ? $buyerId : $sellerId;

        $evaluation = new Evaluation();
        $evaluation->order_id = $order->id;
        $evaluation->user_id = $evaluatorId;
        $evaluation->target_user_id = $targetUserId;
        $evaluation->rating = (int) $validated['rating'];
        $evaluation->save();

        $order->status = '取引済';
        $order->save();

        if ($evaluatorId === $buyerId) {
            $seller = $order->item->user;
            Mail::to($seller->email)->send(new EvaluationNotification($order, $evaluation));
        }

        return redirect('/');
    }
}