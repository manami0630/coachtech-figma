<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChatRequest;
use App\Models\Chat;
use App\Models\Order;
use App\Models\Evaluation;
use App\Models\ChatRead;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;


class TransactionController extends Controller
{
    public function chat($id)
    {
        $user = auth()->user();

        $order = Order::find($id);

        $item = optional($order->item);

        $address = Address::where('user_id', auth()->id())->first();

        $chats = $item->chats()->with('user')->orderBy('created_at', 'asc')->get();

        $lastAccessTime = session('last_access_time', now());

        $newMessageCount = $chats->filter(function($chat) use ($lastAccessTime) {
            return $chat->created_at > $lastAccessTime;
        })->count();

        session(['new_message_count' => $newMessageCount]);

        session(['last_access_time' => now()]);

        $transactions = Order::with([
            'item',
            'item.chats' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])
        ->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
            ->orWhereHas('item', function ($q2) use ($user) {
                $q2->where('user_id', $user->id);
            });
        })
        ->whereIn('status', ['取引中', '取引済'])
        ->get()
        ->sortByDesc(function ($order) {
            return optional($order->item->chats->first())->created_at
            ?? \Carbon\Carbon::createFromDate(1970, 1, 1);
        })
        ->values();

        $isSeller = $order->item && $order->item->user_id == $user->id;

        $hasEvaluated = Evaluation::where('order_id', $order->id)
        ->where('user_id', Auth::id())
        ->exists();

        ChatRead::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $item->id],
            ['last_read_at' => now()]
        );

        $buyerHasEvaluated = Evaluation::where('order_id', $order->id)
        ->where('user_id', $order->user_id)
        ->exists();

        return view('chat', compact('item','user','chats','order','transactions','isSeller', 'newMessageCount','hasEvaluated','buyerHasEvaluated','address'));
    }

    public function store(ChatRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'content' => $validated['content'],
            'item_id' => $validated['item_id'],
            'user_id' => $validated['user_id'],
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/chats');
            $data['image'] = basename($path);
        }

        Chat::create($data);

        return redirect()->back();
    }

    public function update(Request $request, Chat $chat)
    {
        $request->validate([
            'content' => 'required|max:400',
        ]);

        $chat->content = $request->input('content');
        $chat->save();

        return redirect()->back();
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();
        return redirect()->back();
    }

    public function read(Request $request, $item_id)
    {
        $user = auth()->user();

        ChatRead::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $item_id],
            ['last_read_at' => now()]
        );

        return response()->json(['status' => 'ok']);
    }
}